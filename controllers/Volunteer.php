<?php

namespace controllers;


class Volunteer extends Controller {

    private static $requiredFields = ['name','country', 'email', 'birthdate', 'startO', 'helpDesc'];
    private static $actions = ['register', 'search', 'list'];

    public function addView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Volunteer/add.twig');

    }

    public function editView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $query = "SELECT * 
            FROM volunteers
            WHERE id = :id AND userID = :userID";
        $result = DbProvider::select( $query, ['id' => $id, 'userID' => User::getUserID()] );
        $result = self::prepareData($result[0]);
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Volunteer/add.twig',
            [ 'data' => str_replace('\"','\\\\"',json_encode($result, JSON_UNESCAPED_UNICODE)) ]
        );

    }

    public function listView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $query = "SELECT * 
            FROM volunteers
            WHERE userID = " . User::getUserID() . "
            ORDER BY id";
        $result = DbProvider::select( $query );

        if (count($result) === 0) {
            return $this->addView();
        }

        foreach ($result as &$vol) {

            $vol = self::decode(array_filter($vol));

            $vol['languages'] = $this->getLanguages($vol);
            $vol['competitorExp'] = $this->getCompetitorExp($vol);

        }

        return TemplateProvider::render('Volunteer/list.twig',
            [ 'volunteers' => $result, 'title' => 'Volunteers list', 'edit' => true ]
        );

    }

    private function getLanguages(array $data) {

        $result = [];

        if ( !empty($data['languages']) ) {
            foreach ( $data['languages'] as $lang => $desc ) {
                if ( isset( $desc['level'] ) ) {
                    $result[] = "{$lang} ({$desc['level']})";
                }
            }
        }

        return implode(', ', $result);

    }

    private function getCompetitorExp(array $data) {

        if ( !empty($data['competitorExp']) ) {
            $result = [];
            foreach ($data['competitorExp'] as $type => $value) {
                $result[] = "{$value} {$type} events";
            }
            $result = 'compete in ' . implode(', ', $result);
        } else {
            $result = 'Competitor expirience not provided';
        }

        return $result;

    }

    private function getPreferredContinents(array $data) {

        return empty($data['preferredContinents']) ? '' : implode(', ', array_keys($data['preferredContinents']));

    }

    private function getSkills(array $data) {

        $result = [];
        $skillKeys = ['mappingDesc', 'coachDesc','itDesc','eventDesc','teacherDesc'];
        foreach ($skillKeys as $skill) {
            if ( ! empty( $data[$skill] ) ) {
                $info = '';
                foreach ( $data[$skill] as $key => $value ) {
                    if ( $key === 'info' ) {
                        $info = "{$key}: {$value}";
                    } else {
                        $result[$skill][] = ucfirst($key);
                    }
                }
                $result[$skill] = '<b>' . ucfirst(str_replace('Desc','',$skill)).' </b>: '
                    . implode( ', ', $result[$skill] )
                    . (empty($info) ? '' : ", {$info}");
            }
        }

        if (!empty($data['otherSkills'])) {
            $result['otherSkills'] = '<b>Other: ' . $data['otherSkills'] . ' </b>';
        }

        $result = implode('<br>',$result);

        return $result;

    }

    private function getAge(array $data) {
        if ( ! empty( $data['birthdate'] ) ) {
            $d1 = \DateTime::createFromFormat('Y-m-d', $data['birthdate']);
            if ($d1) {
                $d2   = new \DateTime();
                $diff = $d2->diff( $d1 );
                return $diff->y;
            }
        }
        return 0;
    }

    public function searchView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Volunteer/search.twig');

    }

    public function previewView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $query = "SELECT v.*, c.name AS countryName 
            FROM volunteers v
                LEFT JOIN countries c ON v.country=c.id
            WHERE v.id = :id";
        $result = DbProvider::select( $query, ['id' => $id] );
        $result = self::decode(array_filter($result[0]));

        $result['languages'] = $this->getLanguages($result);
        $result['competitorExp'] = $this->getCompetitorExp($result);
        $result['preferredContinents'] = $this->getPreferredContinents($result);
        $result['age'] = $this->getAge($result);
        $result['skills'] = $this->getSkills($result);

        $render = [
            'data' => $result,
            'projects' => Project::getOptionList()
        ];

        return TemplateProvider::render('Volunteer/preview.twig', $render);

    }

    public function contact() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $id = $_POST['id'];
        $projectID = $_POST['project'];

        $query = "SELECT * 
            FROM invitations
            WHERE volunteerID = {$id} AND projectID={$projectID}";
        $result = DbProvider::select( $query );
        if (count($result) > 0) {
            return Platform::error( 'Volunteer already contacted for this project!' );
        }

        $query = "SELECT * 
            FROM volunteers
            WHERE id = {$id}";
        $result = DbProvider::select( $query );
        $vData = self::decode(array_filter($result[0]));

        $query = "SELECT * 
            FROM projects
            WHERE id = {$projectID}";
        $result = DbProvider::select( $query );
        $pData = self::decode(array_filter($result[0]));

        $params = [
            'volunteerID' => $id,
            'projectID' => $projectID,
            'key' => md5(uniqid($id, true)),
            'status' => 'new',
            'authorID' => User::getUserID(),
        ];

        $query = "INSERT INTO invitations (`volunteerID`,`projectID`,`key`,`status`,`authorID`)
                VALUES (:volunteerID,:projectID,:key,:status,:authorID)";
        $statement = DbProvider::getInstance()->prepare( $query );
        $success = $statement->execute( $params );

        $mailText = TemplateProvider::render('Mail/invitation.twig',['volunteer' => $vData, 'key' => $params['key'], 'project' => $pData]);
        $mailSent = Mailer::send($vData['email'],$vData['name'], 'Invitation to orienteering project', $mailText);

        $params = [
            'status' => $mailSent ? 'mail sent' : 'mail failed',
            'key' => $params['key'],
        ];

        $query = "UPDATE invitations SET `status`=:status WHERE `key`=:key";
        $statement = DbProvider::getInstance()->prepare( $query );
        $success = $statement->execute( $params );

        return TemplateProvider::render('Volunteer/contact.twig',
            ['result' => 'Invitation to volunteer ' . ($mailSent ? 'was sent' : 'unexpectedly failed')]
        );

    }

    public function add() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'register');

        // show validation errors
        if (!empty($params['errors'])) {
            return Platform::error( $params['errors'] );
        }

        //error_log(var_export($params,true)."\n");

        // prepare and run query
        if (isset($params['id'])) {
            $valueMasks = implode(',',
                array_map(function($v) { return "{$v} = :{$v}"; },array_keys($params))
            );
            $query = "UPDATE volunteers
                SET {$valueMasks}
                WHERE id= :id";
        } else {
            $valueMasks = implode(',',
                array_map(function($v) { return ':'.$v; },array_keys($params))
            );
            $keys = implode(',',array_keys($params));
            $query = "INSERT INTO volunteers ({$keys})
                VALUES ({$valueMasks})";
        }

        $statement = DbProvider::getInstance()->prepare( $query );
        $success = $statement->execute( $params );

        return $success ? $this->listView() : Platform::error( 'Unexpected error' );

    }

    /**
     * Validate params and set 'errors' element on errors
     *
     * @param array $params
     * @param string $action
     *
     * @return array
     * @throws \Exception
     */
    private function validate(array $params, string $action) : array {

        if ( empty($action) || !in_array($action,self::$actions,true) ) {
            throw new \Exception('Invalid validate action');
        }

        foreach ($params as $key => &$param) {

            // remove all empty array elements
            if (is_array($param)) {
                $param = array_filter($param);
            }

            // remove empty elements
            if (empty($param)) {
                unset($params[$key]);
            }

            // convert arrays to JSON
            if (is_array($param) && $action === 'register') {
                $param = str_replace('\"','\\"',json_encode($param, JSON_UNESCAPED_UNICODE));
            }

        }

        if ($action === 'search') {
            return $params;
        }

        $params['userID'] = User::getUserID();

        // check for required fields
        foreach (self::$requiredFields as $key) {
            if (!isset($params[$key])) {
                $params['errors'][] = "Required field `{$key}` is missing`";
            }
        }

        if (empty($params["iAgreeWithTerms"])) {
            $params['errors'][] = "You are not agreed with disclaimer";
        } else {
            unset($params["iAgreeWithTerms"]);
        }
        unset($params['MAX_FILE_SIZE']);

        // fill tech fields
        if (isset($params['mappingDesc'])) { $params['mappingSkilled'] = 1; }
        if (isset($params['coachDesc'])) { $params['coachSkilled'] = 1; }
        if (isset($params['itDesc'])) { $params['itSkilled'] = 1; }
        if (isset($params['eventDesc'])) { $params['eventSkilled'] = 1; }
        if (isset($params['teacherDesc'])) { $params['teacherSkilled'] = 1; }

        return $params;

    }

    /**
     * Return MySQL condition string for each param name
     * @param string $param parameter name
     *
     * @return string
     */
    private function createCondition(string $param, $value) : string {

        switch ($param) {
            case 'minage':
                $condition = "YEAR(now()) - YEAR(birthdate) - (DATE_FORMAT(now(), '%m%d') < DATE_FORMAT(birthdate, '%m%d')) >= :{$param}";
                break;
            case 'maxage':
                $condition = "YEAR(now()) - YEAR(birthdate) - (DATE_FORMAT(now(), '%m%d') < DATE_FORMAT(birthdate, '%m%d')) <= :{$param}";
                break;
            case 'oyears':
                $condition = "YEAR(now()) - startO >= :{$param}";
                break;
            case 'maxWorkDuration':
                $condition = "{$param} >= :{$param}";
                break;
            case 'timeToStart':
                $condition = "DATE_FORMAT('{$param}' ,'%Y-%m-01') >= :{$param}";
                break;
            // json arrays
            case 'competitorExp':
            case 'teacherDesc':
            case 'languages':
                $condition = [];
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $condition[] = "{$param}->>\"$.{$key2}.{$key3}\" = :{$param}_{$key2}_{$key3}";
                        }
                    } else {
                        if (strpos($key2,'Other') !== false) {
                            $condition[] = "{$param}->>\"$.{$key2}\" LIKE :{$param}_{$key2}";
                        } else {
                            $condition[] = "{$param}->>\"$.{$key2}\" = :{$param}_{$key2}";
                        }
                    }
                }
                $condition = implode(' AND ', $condition);
                break;
            default:
                $condition = "{$param} = :{$param}";
                break;
        }

        return $condition;

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'search');

        // prepare and run query
        $conditions = '';
        if (!empty($params)) {
            $conditions = [];
            foreach ($params as $key => $value) {
                $conditions[] = $this->createCondition($key, $value);
            }
            $conditions = 'WHERE ' . implode(' AND ', $conditions);

            foreach ($params as $key => &$value) {
                switch ($key) {
                    // 1-dimension json arrays
                    case 'competitorExp':
                    case 'teacherDesc':
                        foreach ($value as $key2 => $value2) {
                            $params["{$key}_{$key2}"] = $value2;
                        }
                        unset($params[$key]);
                        break;
                    // 2-dimension json arrays
                    case 'languages':
                        foreach ($value as $key2 => $value2) {
                            if (strpos($key2, 'Other') !== false) {
                                $params["{$key}_{$key2}"] = "'%$value2%'";
                            } else {
                                foreach ($value2 as $key3 => $value3) {
                                    $params["{$key}_{$key2}_{$key3}"] = $value3;
                                }
                            }
                        }
                        unset($params[$key]);
                        break;
                }
            }
        }

        $query = "SELECT * 
            FROM volunteers
            {$conditions}
            ORDER BY id";
        $found = DbProvider::select( $query , $params );

        foreach ($found as &$vol) {

            $vol = self::decode(array_filter($vol));

            $vol['languages'] = $this->getLanguages($vol);
            $vol['competitorExp'] = $this->getCompetitorExp($vol);

        }

        return TemplateProvider::render('Volunteer/list.twig',
            [ 'volunteers' => $found, 'title' => 'Search results' ]
        );

    }

    public function visitView(string $key) : string {

        return TemplateProvider::render('Volunteer/visit.twig');

    }

    public function excludeView(string $key) : string {

        return TemplateProvider::render('Volunteer/exclude.twig');

    }

}
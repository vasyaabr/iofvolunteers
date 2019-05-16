<?php

namespace controllers;


class Volunteer extends Controller {

    private static $requiredFields = ['name','country', 'email', 'birthdate', 'startO', 'helpDesc'];
    private static $actions = ['register', 'search', 'list'];

    public function addView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Volunteer/add.twig', [ 'data' => [] ]);

    }

    public function editView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $query = "SELECT * 
            FROM volunteers
            WHERE id = {$id}";
        $result = DbProvider::select( $query );
        $result = self::prepareData($result[0]);
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Volunteer/add.twig',
            [ 'data' => json_encode($result,JSON_UNESCAPED_UNICODE) ]
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
            [ 'volunteers' => $result, 'title' => 'Volunteers list' ]
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
            $result = 'Not provided';
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
                foreach ( $data[$skill] as $key => $value ) {
                    if ( $key === 'info' ) {
                        $result[$skill][] = "{$key}: {$value}";
                    } else {
                        $result[$skill][] = ucfirst($key);
                    }
                }
                $result[$skill] = '<b>' . ucfirst(str_replace('Desc','',$skill)).' </b>: ' . implode( ', ', $result[$skill] );
            }
        }

        if (!empty($data['otherSkills'])) {
            $result['otherSkills'] = $data['otherSkills'];
        }

        $result = implode('<br>',$result);

        return $result;

    }

    private function getAge(array $data) {
        if ( ! empty( $data['birthdate'] ) ) {
            $d1 = \DateTime::createFromFormat('Y-m-d H:i:s', $data['birthdate']);
            $d2 = new \DateTime();
            $diff = $d2->diff( $d1 );
            return $diff->y;
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

        $query = "SELECT * 
            FROM volunteers
            WHERE id = {$id}";
        $result = DbProvider::select( $query );
        $result = self::decode(array_filter($result[0]));

        $result['languages'] = $this->getLanguages($result);
        $result['competitorExp'] = $this->getCompetitorExp($result);
        $result['preferredContinents'] = $this->getPreferredContinents($result);
        $result['age'] = $this->getAge($result);
        $result['skills'] = $this->getSkills($result);
        error_log(var_export($result,true)."\n");

        return TemplateProvider::render('Volunteer/preview.twig',
            [ 'data' => $result ]
        );

    }

    public function contact(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        // Temporary stub
        $projectID = 1;

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

        $mailText = TemplateProvider::render('Mail/invitation.twig',['volunteer' => $vData, 'key' => $params['key']]);
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

        //error_log(var_export($_POST,true)."\n");

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
        //error_log("{$query}\n");

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
                $param = json_encode($param, JSON_UNESCAPED_UNICODE);
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
    private function createCondition(string $param) : string {

        switch (trim($param)) {
            case 'minage':
                $condition = "YEAR(now()) - YEAR(birthdate) - (DATE_FORMAT(now(), '%m%d') < DATE_FORMAT(birthdate, '%m%d')) >= :{$param}";
                break;
            case 'maxage':
                $condition = "YEAR(now()) - YEAR(birthdate) - (DATE_FORMAT(now(), '%m%d') < DATE_FORMAT(birthdate, '%m%d')) <= :{$param}";
                break;
            case 'oyears':
                $condition = "YEAR(now()) - YEAR(oStart) - (DATE_FORMAT(now(), '%m%d') < DATE_FORMAT(oStart, '%m%d')) >= :{$param}";
                break;
            case 'maxWorkDuration':
                $condition = "{$param} >= :{$param}";
                break;
            case 'timeToStart_m':
            case 'timeToStart_y':
            case 'competitorExp':
            case 'languages':
            case 'teacherDesc':
                // TODO
                //break;
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

        error_log(var_export($params,true)."\n");

        // prepare and run query
        $conditions = implode(' AND ',
            array_map(
                array($this, 'createCondition') ,
                array_keys($params)
            )
        );
        error_log("{$conditions}\n");

        $query = "SELECT * 
            FROM volunteers
            WHERE {$conditions}";
        error_log("{$query}\n");
        $found = DbProvider::select( $query , $params );

        return TemplateProvider::render('Volunteer/list.twig',
            [ 'volunteers' => $found, 'title' => 'Search results' ]
        );

    }

}
<?php

namespace iof;


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

        return TemplateProvider::render('Volunteer/add.twig',
            [ 'data' => json_encode(self::prepareData($result[0]),JSON_UNESCAPED_UNICODE) ]
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

            // prepare languages string
            if (!empty($vol['languages'])) {
                $langResults = [];
                foreach ($vol['languages'] as $lang => $desc) {
                    if (isset($desc['level'])) {
                        $langResults[] = "{$lang} ({$desc['level']})";
                    }
                }
                $vol['languages'] = implode(', ', $langResults);
            }

            // prepare competitor experience string
            if (!empty($vol['competitorExp'])) {
                $expResults = [];
                foreach ($vol['competitorExp'] as $type => $value) {
                    $expResults[] = "{$value} {$type} events";
                }
                $vol['competitorExp'] = 'compete in ' . implode(', ', $expResults);
            }

        }

        return TemplateProvider::render('Volunteer/list.twig',
            [ 'volunteers' => $result, 'title' => 'Volunteers list' ]
        );

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

        return TemplateProvider::render('Volunteer/preview.twig',
            [ 'data' => $result ]
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
        $valueMasks = implode(',',
            array_map(function($v) { return ':'.$v; },array_keys($params))
        );
        if (isset($params['id'])) {
            $query = "UPDATE volunteers
                SET {$valueMasks}
                WHERE id= :id";
        } else {
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
<?php

namespace iof;


class Volunteer {

    private static $requiredFields = ['name','country', 'email', 'birthdate', 'startO', 'helpDesc'];
    private static $dateTimeFields = ['birthdate','startO', 'timeToStart'];
    private static $actions = ['register', 'search'];

    public function regShow() : string {

        return TemplateProvider::getInstance()->render('regVolunteer.twig');

    }

    public function searchShow() : string {

        return TemplateProvider::getInstance()->render('searchVolunteer.twig');

    }

    public function register() : string {

        if (!User::isAuthenticated()) {
            return 'Error: user not authenticated';
        }

        //error_log(var_export($_POST,true)."\n");

        $params = $this->validate($_POST, 'register');

        // show validation errors
        if (!empty($params['errors'])) {
            return implode("\n", $params['errors']);
        }

        //error_log(var_export($params,true)."\n");

        // prepare and run query
        $keys = implode(',',array_keys($params));
        $valueMasks = implode(',',
            array_map(function($v) { return ':'.$v; },array_keys($params))
        );

        $query = "INSERT INTO volunteers ({$keys})
                VALUES ({$valueMasks})";
        //error_log("{$query}\n");
        $statement = DbProvider::getInstance()->prepare( $query );
        $success = $statement->execute( $params );

        return $success ? 'OK' : 'Error';

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

        $params['userID'] = User::getUserID();

        if ($action === 'search') {
            return $params;
        }

        // check for required fields
        foreach (self::$requiredFields as $key) {
            if (!isset($params[$key])) {
                $params['errors'][] = "Required field `{$key}` is missing`";
            }
        }

        // check datetime fields
        foreach (self::$dateTimeFields as $key) {
            if (isset($params[$key])) {
                $params[$key] = "{$params[$key]}-01-01 00:00:00";
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
            return 'Error: user not authenticated';
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
        $found = DbProvider::run( $query , $params );

        return TemplateProvider::getInstance()->render('volunteersList.twig', $found);

    }

}
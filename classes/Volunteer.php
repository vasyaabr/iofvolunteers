<?php

namespace iof;


class Volunteer {

    public static $requiredFields = ['name','country', 'email', 'birthdate', 'startO', 'helpDesc'];

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

        $params = $this->validate($_POST);

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
     * @param array $params
     *
     * @return array
     */
    private function validate(array $params) : array {

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
            if (is_array($param)) {
                $param = json_encode($param, JSON_UNESCAPED_UNICODE);
            }

        }

        // check for required fields
        foreach (self::$requiredFields as $value) {
            if (!isset($params[$value])) {
                $params['errors'][] = "Required field `{$value}` is missing`";
            }
        }

        // check datetime fields
        $dateTimeFields = ['birthdate','startO', 'timeToStart'];
        foreach ($dateTimeFields as $value) {
            if (isset($params[$value])) {
                $params[$value] = "{$params[$value]}-01-01 00:00:00";
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

        $params['userID'] = User::getUserID();

        return $params;

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return 'Error: user not authenticated';
        }

        error_log(var_export($_POST,true)."\n");

        $params = $_POST;

        // fill other fields
        $params['userID'] = User::getUserID();

        error_log(var_export($params,true)."\n");

        // prepare and run query
        $conditions = implode(' AND ',
            array_map(
                function($v) { return "{$v} = :{$v}"; } ,
                array_keys($params)
            )
        );

        $query = "SELECT * 
            FROM volunteers
            WHERE {$conditions}";
        error_log("{$query}\n");
        $found = DbProvider::run( $query , $params );

        return TemplateProvider::getInstance()->render('volunteersList.twig', $found);

    }

}
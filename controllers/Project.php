<?php

namespace controllers;


class Project extends Controller {

    private static $requiredFields = [];

    public function addView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Project/add.twig');

    }

    public function editView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $query = "SELECT * 
            FROM projects
            WHERE id = :id AND userID = :userID";
        $result = DbProvider::select( $query, ['id' => $id, 'userID' => User::getUserID()] );
        $result = self::prepareData($result[0]);
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Project/add.twig',
            [ 'data' => json_encode($result,JSON_UNESCAPED_UNICODE) ]
        );

    }

    public function listView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $query = "SELECT * 
            FROM projects
            WHERE userID = " . User::getUserID() . "
            ORDER BY id";
        $result = DbProvider::select( $query );

        if (count($result) === 0) {
            return $this->addView();
        }

        foreach ($result as &$vol) {

            $vol = self::decode(array_filter($vol));

        }

        return TemplateProvider::render('Project/list.twig',
            [ 'projects' => $result, 'title' => 'Projects list' ]
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

        // prepare and run query
        if (isset($params['id'])) {
            $valueMasks = implode(',',
                array_map(function($v) { return "{$v} = :{$v}"; },array_keys($params))
            );
            $query = "UPDATE projects
                SET {$valueMasks}
                WHERE id= :id";
        } else {
            $valueMasks = implode(',',
                array_map(function($v) { return ':'.$v; },array_keys($params))
            );
            $keys = implode(',',array_keys($params));
            $query = "INSERT INTO projects ({$keys})
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

        return $params;

    }

}
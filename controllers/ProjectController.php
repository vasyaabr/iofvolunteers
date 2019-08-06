<?php

namespace controllers;

use models\User;
use models\Project;

class ProjectController extends Controller {

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

        $result = self::prepareData( Project::getSingle(['id' => $id, 'userID' => User::getUserID()]) );
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Project/add.twig',
            [ 'data' => self::json_enc($result) ]
        );

    }

    public function listView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = Project::get(['userID' => User::getUserID()]);

        if (count($result) === 0) {
            return $this->addView();
        }

        foreach ($result as &$vol) {
            $vol = self::decode(array_filter($vol));
            $vol['offer'] = Project::getOffer($vol);
        }

        return TemplateProvider::render('Project/list.twig',
            [ 'projects' => $result, 'title' => 'Projects list', 'edit' => true ]
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

        $success = isset($params['id']) ? Project::update($params) : Project::add($params);

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
            if (is_array($param) && $action === 'register') {
                $param = self::json_enc($param);
            }

        }

        if ($action === 'search') {
            return $params;
        }

        $params['userID'] = User::getUserID();

        if (empty($params["iAgreeWithTerms"])) {
            $params['errors'][] = "You are not agreed with disclaimer";
        } else {
            unset($params["iAgreeWithTerms"]);
        }

        return $params;

    }

    public static function getOptionList() : string {

        $list = Project::get( [ 'userID' => User::getUserID() ], ['id AS id', 'concat(name," - ",place) as name'] );

        return TemplateProvider::render('Common/options.twig', [ 'options' => $list ] );

    }

    public function searchView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Project/search.twig');

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'search');

        $found = Project::get($params);

        foreach ($found as &$vol) {

            $vol = self::decode(array_filter($vol));
            $vol['offer'] = Project::getOffer($vol);

        }

        return TemplateProvider::render('Project/list.twig',
            [ 'projects' => $found, 'title' => 'Project search results' ]
        );

    }

}
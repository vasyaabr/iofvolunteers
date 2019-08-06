<?php

namespace controllers;

use models\User;
use models\Host;

class HostController extends Controller {

    public function addView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Host/add.twig');

    }

    public function editView(string $id) : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = self::prepareData( Host::getSingle(['id' => $id, 'userID' => User::getUserID()]) );
        $result['iAgreeWithTerms'] = 1;

        return TemplateProvider::render('Host/add.twig',
            [ 'data' => self::json_enc($result) ]
        );

    }

    public function listView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $result = Host::get(['userID' => User::getUserID()]);

        if (count($result) === 0) {
            return $this->addView();
        }

        foreach ($result as &$vol) {
            $vol = self::decode(array_filter($vol));
            $vol['languages'] = Host::getLanguages($vol);
        }

        return TemplateProvider::render('Host/list.twig',
            [ 'hosts' => $result, 'title' => 'Hosts list', 'edit' => true ]
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

        $success = isset($params['id']) ? Host::update($params) : Host::add($params);

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

        $list = Host::get( [ 'userID' => User::getUserID() ], ['id AS id', 'concat(name," - ",place) as name'] );

        return TemplateProvider::render('Common/options.twig', [ 'options' => $list ] );

    }

    public function searchView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('Host/search.twig');

    }

    public function search() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $params = $this->validate($_POST, 'search');

        $found = Host::get($params);

        foreach ($found as &$vol) {

            $vol = self::decode(array_filter($vol));

        }

        return TemplateProvider::render('Host/list.twig',
            [ 'hosts' => $found, 'title' => 'Search results' ]
        );

    }

}
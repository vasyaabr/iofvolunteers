<?php

namespace controllers;


use models\Country;
use models\User;

class CountryController {

    public function getOptionList() {

        $list = Country::get(
            [ 'userID' => User::getUserID() ],
            [ 'id AS id', 'name AS name' ]
        );

        return TemplateProvider::render('Common/options.twig', [ 'options' => $list ] );

    }

}
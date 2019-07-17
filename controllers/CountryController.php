<?php

namespace controllers;


use models\Country;

class CountryController {

    public function getOptionList() {

        $list = Country::get(
            [ ],
            [ 'id AS id', 'name AS name' ]
        );

        return TemplateProvider::render('Common/options.twig', [ 'options' => $list ] );

    }

}
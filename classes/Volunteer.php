<?php

namespace iof;


class Volunteer {

    public function regShow() : string {

        return TemplateProvider::getInstance()->render('regVolunteer.twig');

    }

    public function searchShow() : string {

        return TemplateProvider::getInstance()->render('searchVolunteer.twig');

    }

    public function register() : string {

        error_log(var_export($_POST,true));
        return 'OK';

    }

    public function search() : string {

        error_log(var_export($_POST,true));
        return 'OK';

    }

}
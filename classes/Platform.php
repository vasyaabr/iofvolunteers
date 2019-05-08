<?php

namespace iof;

class Platform {

    public function load() : string {

        $params = ['welcome' => '<b>Welcome to the Global Orienteering Volunteer Platform!<br>
                        <br>You need to register in order to access the platform! If already registered, then sign in
                        first...</b>'];

        if (!empty($_SESSION['user'])) {
            $params['welcome'] = '';
            if (!empty($_SESSION['auth_done'])) {
                $names             = explode( ' ', trim( $_SESSION['user']['name'] ) );
                $params['welcome'] = "Welcome {$names[0]}! Be part of the Global Orienteering Volunteer Network by clicking on any of the icons below...";
                unset( $_SESSION['auth_done'] );
            }
        }

        return TemplateProvider::getInstance()->render('index.twig', $params);

    }

}
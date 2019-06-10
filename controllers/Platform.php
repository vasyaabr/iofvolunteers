<?php

namespace controllers;

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

        return TemplateProvider::render('index.twig', $params);

    }

    public static function error($message) : string {

        if (is_array($message)) {
            $message = implode("<br>\n",$message);
        }

        $params = [
            'referer' => $_SERVER['HTTP_REFERER'],
            'message' => $message,
        ];

        return TemplateProvider::render('error.twig', $params);
    }

    public static function error404() : string {

        $params = [
            'referer' => $_SERVER['HTTP_REFERER'],
        ];

        return TemplateProvider::render('404.twig', $params);
    }

}
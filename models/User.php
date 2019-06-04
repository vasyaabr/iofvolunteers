<?php

namespace models;


class User extends Model {

    public static $table = 'users';
    public static $requiredFields = ['name','country', 'email', 'login', 'password'];

    /**
     * Return true, if user is authenticated
     * @return bool
     */
    public static function isAuthenticated() : bool {

        return !empty($_SESSION['user']);

    }

    /**
     * Return authenticated user ID
     * @return int
     */
    public static function getUserID() : int {

        return self::isAuthenticated() ? $_SESSION['user']['id'] : 0;

    }


    /**
     * Default password encode
     * @param string $password
     *
     * @return string
     */
    public static function encodePassword(string $password) : string {

        return hash('sha256', $password);

    }

}
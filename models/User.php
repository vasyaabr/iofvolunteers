<?php

namespace models;

use Respect\Validation\Validator as v;

class User extends Model {

    public static $table = 'users';

    /**
     * Local validators
     * @return array
     */
    public static function getValidators() : array {

        return array_replace(parent::getValidators(),
            [
                'email' => v::notEmpty()->email(),
                'login' => v::notEmpty(),
                'password' => v::notEmpty()->length(8),
                'name' => v::notEmpty(),
                'country' => v::notEmpty(),
            ]
        );

    }

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

    /**
     * Generate random password
     * @return string
     */
    public static function randomPassword() : string {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = [];
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass);
    }

}
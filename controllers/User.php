<?php

namespace controllers;

class User {

    private static $requiredFields = ['name','country', 'email', 'login', 'password'];

    public function add() : string {

        $params = $this->validate($_POST, 'register');

        if (!empty($params['errors'])) {
            return Platform::error( $params['errors'] );
        }

        $query = 'INSERT INTO users (id,name,country,email,login,password)
                VALUES (DEFAULT, :name, :country, :email, :login, :password)';
        $statement = DbProvider::getInstance()->prepare( $query );
        $success = $statement->execute( $params );

        if ($success) {
            $this->authenticate($params['login']);
        }

        return $success ? (new Platform())->load() : Platform::error( 'Unexpected error' );

    }

    /**
     * Set user session param, if login (and password, if provided) is correct
     * @param string $login
     * @param string $passwordHash Encoded password
     *
     * @return bool
     * @throws \Error
     */
    private function authenticate(string $login, string $passwordHash = '') : bool {

        if (empty($passwordHash)) {
            $params = [':login' => $login];
            $query = "SELECT * FROM users WHERE login=:login OR email=:login";
        } else {
            $params = [':login' => $login, ':passwordHash' => $passwordHash ];
            $query = "SELECT * FROM users WHERE (login=:login OR email=:login) and password=:passwordHash";
        }

        $users = DbProvider::select($query, $params);
        $success = count($users) === 1;

        if ($success) {
            $_SESSION['user'] = $users[0];
            $_SESSION['auth_done'] = true;
        }

        return $success;

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
    private static function encodePassword(string $password) : string {

        return hash('sha256', $password);

    }

    /**
     * Validate params and set 'errors' element on errors
     * @param array $params
     *
     * @return array
     */
    private function validate(array $params, string $action) : array {

        /**
         * TODO: validate params, which is set
         * - email unique
         * - login unique
         */

        $required = $action === 'register' ? self::$requiredFields : ['login','password'];
        foreach ($required as $key) {
            if (!isset($params[$key])) {
                $params['errors'][] = "Required field `{$key}` is missing`";
            }
        }

        if (isset($params['email']) && filter_var($params['email'], FILTER_VALIDATE_EMAIL) === false) {
            $params['errors'][] = 'Invalid email';
        }

        if (isset($params['password'])) {
            $params['password'] = self::encodePassword( $params['password'] );
        }

        if (isset($params['name'])) {
            $params['name'] = ucfirst($params['name']);
        }

        return $params;

    }

    public function signIn() : string {

        $params = $this->validate([
            'login' => $_POST['login'] ?? null,
            'password'    => $_POST['password'] ?? null,
        ], 'signin');

        if (!empty($params['errors'])) {
            return Platform::error( $params['errors'] );
        }

        $success = $this->authenticate($params['login'], $params['password']);

        return $success ? (new Platform())->load() : Platform::error( 'Invalid login or password' );

    }

    public function logout() : string {

        unset($_SESSION['user'],$_SESSION['auth_done']);
        return (new Platform())->load();

    }

}
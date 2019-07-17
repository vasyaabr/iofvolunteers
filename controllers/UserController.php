<?php

namespace controllers;

use models;
use models\User;

class UserController {

    public function add() : string {

        $params = $this->validate($_POST, 'register');

        if (!empty($params['errors'])) {
            return Platform::error( $params['errors'] );
        }

        $success = User::add($params);

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

        $params = [['login' => $login, 'email' => $login]];
        if (!empty($passwordHash)) {
            $params += ['passwordHash' => $passwordHash];
        }

        $user = User::getSingle($params);

        if (!empty($user)) {
            $_SESSION['user'] = $user;
            $_SESSION['auth_done'] = true;
        }

        return !empty($user);

    }

    /**
     * Validate params and set 'errors' element on errors
     * @param array $params
     *
     * @return array
     */
    private function validate(array $params, string $action) : array {

        $required = $action === 'register' ? User::$requiredFields : ['login','password'];
        foreach ($required as $key) {
            if (!isset($params[$key])) {
                $params['errors'][] = "Required field `{$key}` is missing`";
            }
        }

        if (isset($params['email']) && filter_var($params['email'], FILTER_VALIDATE_EMAIL) === false) {
            $params['errors'][] = 'Invalid email';
        }

        if (empty($params['errors'])) {
            $p = [['login' => $params['login'], 'email' => $params['email']]];
            if (!empty(User::get($p))) {
                $params['errors'][] = 'Login or email already used';
            }
        }

        if (isset($params['password'])) {
            $params['password'] = User::encodePassword( $params['password'] );
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
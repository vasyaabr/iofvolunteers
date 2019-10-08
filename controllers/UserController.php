<?php

namespace controllers;

use models;
use models\User;

class UserController {

    public function add() : string {

        $validationErrors = User::validateAll($_POST, true);
        if (!empty($validationErrors)) {
            return Platform::error( implode("<br>",$validationErrors) );
        }

        $p = [['login' => $_POST['login'], 'email' => $_POST['email']]];
        if (!empty(User::get($p))) {
            return Platform::error( 'Login or email already used' );
        }

        $_POST['password'] = User::encodePassword( $_POST['password'] );
        $_POST['name'] = ucfirst($_POST['name']);

        $success = User::add($_POST);

        if ($success) {
            $this->authenticate($_POST['login']);
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
            $params += ['password' => $passwordHash];
        }

        $user = User::getSingle($params);

        if (!empty($user)) {
            $_SESSION['user'] = $user;
            $_SESSION['auth_done'] = true;
        }

        return !empty($user);

    }

    public function signIn() : string {

        $validationErrors =  User::validateList(['login','password'], $_POST);
        if (!empty($validationErrors)) {
            return Platform::error( implode("<br>",$validationErrors) );
        }

        $success = $this->authenticate($_POST['login'], User::encodePassword($_POST['password']) );

        return $success ? (new Platform())->load() : Platform::error( 'Invalid login or password' );

    }

    public function logout() : string {

        unset($_SESSION['user'],$_SESSION['auth_done']);
        return (new Platform())->load();

    }

    public function restoreView() : string {

        return TemplateProvider::render('User/restore.twig');

    }

    public function restore() : string {

        $validationErrors =  User::validate('email', $_POST['email'] ?? null);
        if (!empty($validationErrors)) {
            return Platform::error( implode("<br>",$validationErrors) );
        }

        $user = User::getSingle(['email' => $_POST['email']]);
        if (empty($user)) {
            return Platform::error( 'Invalid email' );
        }

        $pass = User::randomPassword();
        User::update(['id'=>$user['id'], 'password' => User::encodePassword($pass)]);

        $mailText = TemplateProvider::render( 'Mail/resetPassword.twig', [
            'pass' => $pass,
        ] );
        $mailSent = Mailer::send( $_POST['email'], null, 'Password reset', $mailText );

        return Platform::error( $mailSent ? 'Password successfully reset. Check your email.' : 'Unexpected error' );

    }

    public function changepassView() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        return TemplateProvider::render('User/change.twig');

    }

    public function changepass() : string {

        if (!User::isAuthenticated()) {
            return Platform::error( 'You are not authenticated' );
        }

        $validationErrors =  User::validate('password', $_POST['password'] ?? null);
        if (!empty($validationErrors)) {
            return Platform::error( implode("<br>",$validationErrors) );
        }

        if ($_POST['password'] !== $_POST['passwordretype']) {
            return Platform::error( 'Invalid password retype' );
        }

        if ($_SESSION['user']['password'] !== User::encodePassword($_POST['password'])) {
            return Platform::error( 'Invalid old password' );
        }

        User::update(['id'=>User::getUserID(), 'password' => User::encodePassword($_POST['password'])]);

        return Platform::error( 'Password successfully changed.' );

    }

}
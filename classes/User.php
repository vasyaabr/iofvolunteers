<?php

namespace iof;

class User {

    public function add() : string {

        $params = $this->validate([
            'name' => $_POST['name'] ?? null,
            'country'    => $_POST['country'] ?? null,
            'email'    => $_POST['email'] ?? null,
            'login' => $_POST['login'] ?? null,
            'password'    => $_POST['password'] ?? null,
        ]);

        if (!empty($params['errors'])) {
            return implode(', ', $params['errors']);
        }

        $query = 'INSERT INTO users (id,name,country,email,login,password)
                VALUES (DEFAULT, :name, :country, :email, :login, :password)';
        $statement = DbProvider::getInstance()->prepare( $query );
        $success = $statement->execute( $params );

        if ($success) {
            $this->authenticate($params['login']);
        }

        return $success ? 'OK' : 'Error';

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

        $users = DbProvider::run($query, $params);
        $success = count($users) === 1;

        if ($success) {
            $_SESSION['user'] = $users[0];
            $_SESSION['auth_done'] = true;
        }

        return $success;

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
    private function validate(array $params) : array {

        /** TODO: validate params, which is set
         * - name not empty, set first letter capital
         * - country code in list of countries
         * - email is not empty, valid and unique
         * - login not empty and unique
         * - password not empty
         */

        if (isset($params['password'])) {
            $params['password'] = self::encodePassword( $params['password'] );
        }

        return $params;

    }

    public function signin() : string {

        $params = $this->validate([
            'login' => $_POST['login'] ?? null,
            'password'    => $_POST['password'] ?? null,
        ]);

        if (!empty($params['errors'])) {
            return implode(', ', $params['errors']);
        }

        $success = $this->authenticate($params['login'], $params['password']);

        return $success ? 'OK' : 'Error';

    }

}
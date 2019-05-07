<?php

namespace iof;


class Config {

    public static function init() : void {}

}

define('ENV','DEV'); // DEV/PROD

if (ENV === 'DEV') {
    define( 'DB_ENGINE', 'SQLITE' );
    define( 'DB_PATH', $_SERVER['DOCUMENT_ROOT'] . '/iofvolunteers.db' );
} else {
    define( 'DB_ENGINE', 'MYSQL' );
    define( 'DB_HOST', '' );
    define( 'DB_NAME', '' );
    define( 'DB_USER', '' );
    define( 'DB_PASSWORD', '' );
}

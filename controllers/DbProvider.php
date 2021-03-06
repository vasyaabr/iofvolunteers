<?php

namespace controllers;

/**
 * Class DbProvider
 * Database single entry point
 */
class DbProvider {

    /** @var \PDO */
    private static $instance;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    /**
     * Prepare database instance
     * @return \PDO
     */
    public static function getInstance() : \PDO
    {
        
        if ( is_null(self::$instance) ) {

            self::$instance = new \PDO( "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASSWORD,
                array( \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ) );
            self::$instance->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

        }
        
        return self::$instance;
        
    }

    /**
     * Run database query
     *
     * @param $query string
     * @param $params array
     *
     * @return array
     */
    public static function select(string $query, array $params = null) : array {

        $statement = self::getInstance()->prepare($query);
//        echo $query."<br/>";
//        echo var_export($params,true)."<br/>";
        $success = $statement->execute($params);
        if (!$success) {
            throw new \Error("SQL error in query:\n{$query}");
        }

        return $statement->fetchAll( \PDO::FETCH_ASSOC );

    }
    
}
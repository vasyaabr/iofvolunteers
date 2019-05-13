<?php

namespace iof;

/**
 * Class TemplateProvider
 * Twig templates single entry point
 */
class TemplateProvider {

    /** @var \Twig\Environment */
    private static $instance;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    /**
     * @return \Twig\Environment
     */
    public static function getInstance() : \Twig\Environment {

        if ( is_null(self::$instance) ) {

            $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
            self::$instance = new \Twig\Environment($loader);

        }

        return self::$instance;

    }

    public static function render(string $name, array $context = []) : string {

        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        self::getInstance()->addGlobal('url', $root);
        return self::getInstance()->render($name, $context);

    }

}
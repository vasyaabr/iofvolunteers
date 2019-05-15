<?php

require __DIR__ . '/vendor/autoload.php';

use FastRoute\RouteCollector;
use controllers;
use controllers\Volunteer;
use controllers\User;
use controllers\Platform;
use controllers\Country;

session_start();

// Available routes list
$dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', 'Platform/load');
    $r->addRoute('GET', '/countries', 'Country/getOptionList');
    $r->addRoute('POST', '/register', 'User/add');
    $r->addRoute('POST', '/signin', 'User/signIn');
    $r->addRoute('GET', '/logout', 'User/logout');
    $r->addRoute('GET', '/volunteer/register', 'Volunteer/addView');
    $r->addRoute('POST', '/volunteer/register', 'Volunteer/add');
    $r->addRoute('GET', '/volunteer/search', 'Volunteer/searchView');
    $r->addRoute('POST', '/volunteer/search', 'Volunteer/search');
    $r->addRoute('GET', '/volunteer/list', 'Volunteer/listView');
    $r->addRoute('GET', '/volunteer/edit/{id:\d+}', 'Volunteer/editView');
    $r->addRoute('GET', '/volunteer/preview/{id:\d+}', 'Volunteer/previewView');
    $r->addRoute('GET', '/volunteer/contact/{id:\d+}', 'Volunteer/contact');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

// Testing statement
$uri = str_replace('iofvolunteers/','',$uri);

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        echo Platform::error404();
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        // TODO: Implement 405 page
        break;
    case \FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        list($class, $method) = explode("/", $handler, 2);
        $class =  "\controllers\\{$class}";
        echo call_user_func_array([new $class, $method], $vars);
        break;
}

die();

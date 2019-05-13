<?php

namespace iof;

use FastRoute\RouteCollector;

require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

session_start();

// Available routes list
$dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', 'Platform/load');
    $r->addRoute('GET', '/countries', 'Country/getOptionList');
    $r->addRoute('POST', '/register', 'User/add');
    $r->addRoute('POST', '/signin', 'User/signin');
    $r->addRoute('GET', '/regVolunteer', 'Volunteer/regShow');
    $r->addRoute('POST', '/regVolunteer', 'Volunteer/register');
    $r->addRoute('GET', '/searchVolunteer', 'Volunteer/searchShow');
    $r->addRoute('POST', '/searchVolunteer', 'Volunteer/search');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        // TODO: Implement 404 page
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
        $class =  "\iof\\{$class}";
        echo call_user_func_array([new $class, $method], $vars);
        break;
}

die();

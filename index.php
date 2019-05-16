<?php

require __DIR__ . '/vendor/autoload.php';

use FastRoute\RouteCollector;
use controllers\Platform;

Sentry\init(['dsn' => 'https://f7b4bee347de4d13ad3a7df04370f6b0@sentry.io/1461060' ]);

session_start();

// Available routes list
$dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {

    $r->addRoute('GET', '/', 'Platform/load');
    $r->addRoute('GET', '/countries', 'Country/getOptionList');
    $r->addRoute('POST', '/register', 'User/add');
    $r->addRoute('POST', '/signin', 'User/signIn');
    $r->addRoute('GET', '/logout', 'User/logout');

    // Volunteer routes
    $r->addRoute('GET', '/volunteer/register', 'Volunteer/addView');
    $r->addRoute('POST', '/volunteer/register', 'Volunteer/add');
    $r->addRoute('GET', '/volunteer/search', 'Volunteer/searchView');
    $r->addRoute('POST', '/volunteer/search', 'Volunteer/search');
    $r->addRoute('GET', '/volunteer/list', 'Volunteer/listView');
    $r->addRoute('GET', '/volunteer/edit/{id:\d+}', 'Volunteer/editView');
    $r->addRoute('GET', '/volunteer/preview/{id:\d+}', 'Volunteer/previewView');
    $r->addRoute('POST', '/volunteer/contact', 'Volunteer/contact');
    $r->addRoute('GET', '/volunteer/visit/{key}', 'Volunteer/visitView');
    $r->addRoute('GET', '/volunteer/exclude/{key}', 'Volunteer/excludeView');

    // Project routes
    $r->addRoute('GET', '/project/register', 'Project/addView');
    $r->addRoute('POST', '/project/register', 'Project/add');
    $r->addRoute('GET', '/project/list', 'Project/listView');
    $r->addRoute('GET', '/project/edit/{id:\d+}', 'Project/editView');

});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}

// Testing statement
if (!empty(TEST_SUBFOLDER)) {
    $uri = str_replace(TEST_SUBFOLDER,'',$uri);
}

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

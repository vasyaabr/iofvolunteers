<?php

require __DIR__ . '/vendor/autoload.php';

use FastRoute\RouteCollector;
use controllers\Platform;

ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '16M');
ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);

Sentry\init(['dsn' => 'https://f7b4bee347de4d13ad3a7df04370f6b0@sentry.io/1461060' ]);

session_start();

// Available routes list
$dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {

    $r->addRoute('GET', '/', 'Platform/load');
    $r->addRoute('GET', '/countries', 'CountryController/getOptionList');
    $r->addRoute('POST', '/register', 'UserController/add');
    $r->addRoute('POST', '/signin', 'UserController/signIn');
    $r->addRoute('GET', '/logout', 'UserController/logout');

    // Volunteer routes
    $r->addRoute('GET', '/volunteer/register', 'VolunteerController/addView');
    $r->addRoute('POST', '/volunteer/register', 'VolunteerController/add');
    $r->addRoute('GET', '/volunteer/search', 'VolunteerController/searchView');
    $r->addRoute('POST', '/volunteer/search', 'VolunteerController/search');
    $r->addRoute('GET', '/volunteer/list', 'VolunteerController/listView');
    $r->addRoute('GET', '/volunteer/edit/{id:\d+}', 'VolunteerController/editView');
    $r->addRoute('GET', '/volunteer/preview/{id:\d+}', 'VolunteerController/previewView');
    $r->addRoute('POST', '/volunteer/contact', 'VolunteerController/contact');
    $r->addRoute('GET', '/volunteer/visit/{key}', 'VolunteerController/visitView');
    $r->addRoute('GET', '/volunteer/exclude/{key}', 'VolunteerController/excludeView');
    $r->addRoute('GET', '/volunteer/agree/{key}', 'VolunteerController/agree');
    $r->addRoute('GET', '/volunteer/decline/{key}', 'VolunteerController/decline');

    // Project routes
    $r->addRoute('GET', '/project/register', 'ProjectContoller/addView');
    $r->addRoute('POST', '/project/register', 'ProjectContoller/add');
    $r->addRoute('GET', '/project/list', 'ProjectContoller/listView');
    $r->addRoute('GET', '/project/edit/{id:\d+}', 'ProjectContoller/editView');

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

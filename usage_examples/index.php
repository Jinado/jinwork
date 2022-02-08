<?php

use Jinwork\Application;
use Jinwork\Routing\Request\Request;
use Jinwork\Routing\Request\RequestMethod;
use Jinwork\Routing\Response;
use Jinwork\Routing\Route;
use Jinwork\Routing\Router;

require_once __DIR__ . '/../backend/src/Routing/Router.php';
require_once __DIR__ . '/../backend/src/Routing/Route.php';
require_once __DIR__ . '/../backend/src/Routing/Request/RequestMethod.php';
require_once __DIR__ . '/../backend/src/Application.php';

$router = new Router();

$router->registerRoute(new Route('/', [RequestMethod::GET], function(Request $request, Response $response) {
    $response->send('<h1>Hello, World</h1>');
}));

$router->registerRoute(new Route('/test', [RequestMethod::GET], function(Request $request, Response $response) {
    $response->send('<h1>Test</h1>');
}));

$app = new Application($router);
$app->run();
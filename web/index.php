<?php

require_once __DIR__.'/../vendor/autoload.php';

use Application\Controller\HelloWorldAction;
use Framework\Http\Request;
use Framework\Http\StreamableInterface;
use Framework\Kernel;
use Framework\Routing\Route;
use Framework\Routing\Router;
use Framework\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello', [
    '_controller' => HelloWorldAction::class,
]));
$router = new Router($routes);

$kernel = new Kernel($router);

$response = $kernel->handle(Request::createFromGlobals());

if ($response instanceof StreamableInterface) {
    $response->send();
}

<?php

use Framework\Routing\RouteCollection;
use Framework\Routing\Route;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello', [
    '_controller' => 'Application\Controller\HelloWorldAction',
]));

return $routes;

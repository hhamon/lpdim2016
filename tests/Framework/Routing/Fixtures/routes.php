<?php

use Framework\Routing\RouteCollection;
use Framework\Routing\Route;

$routes = new RouteCollection();
$routes->add('home', new Route('/home', ['_controller' => 'Application\Controller\HomeAction'], ['GET']));
$routes->add('login', new Route('/login'));

return $routes;

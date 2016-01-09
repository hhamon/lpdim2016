<?php

use Framework\Routing\RouteCollection;
use Framework\Routing\Route;

$routes = new RouteCollection();
$routes->add('home', new Route('/home'));
$routes->add('login', new Route('/login'));

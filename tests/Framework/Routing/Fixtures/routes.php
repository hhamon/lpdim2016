<?php

use Framework\Routing\RouteCollection;
use Framework\Routing\Route;

$routes = new RouteCollection();

$routes->add('home', new Route('/home', ['_controller' => 'Application\Controller\HomeAction'], ['GET']));

$routes->add('login', new Route('/login'));

$routes->add('article', new Route('/blog/{year}/{month}/{day}/{slug}.{format}', ['format' => 'html'], ['GET'], [
    'year' => '\d{4}',
    'month' => '\d{2}',
    'day' => '\d{2}',
]));

$routes->add('blog_category', new Route('/blog/{category}', ['category' => 'all']));

return $routes;

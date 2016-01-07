<?php

namespace tests\Framework\Routing;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testIterable()
    {
        $map = [
            'homepage' => new Route('/home'),
            'hello' => new Route('/hello'),
        ];

        $routes = new RouteCollection();
        $routes->add('homepage', $map['homepage']);
        $routes->add('hello', $map['hello']);

        $current = [];
        foreach ($routes as $name => $route) {
            $current[$name] = $route;
        }

        $this->assertSame($map, $current);
    }

    public function testFillRouteCollection()
    {
        $routes = new RouteCollection();
        $routes->add('home', new Route('/home'));
        $routes->add('hello', new Route('/hello'));
        $routes->add('contact', new Route('/contact-us'));

        $this->assertSame(3, $routes->count());
        $this->assertSame(3, count($routes));
        $this->assertCount(3, $routes);
    }
}

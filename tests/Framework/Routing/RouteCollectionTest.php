<?php

namespace Test\Framework\Routing;

use Framework\Routing\Route;
use Framework\Routing\RouteCollection;

class RouteCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testCannotGetRouteName()
    {
        $routes = new RouteCollection();
        $routes->getName(new Route('/r1'));
    }

    public function testGetRouteName()
    {
        $r1 = new Route('/r1');
        $r2 = new Route('/r2');

        $routes = new RouteCollection();
        $routes->add('r1', $r1);
        $routes->add('r2', $r2);

        $this->assertSame('r1', $routes->getName($r1));
        $this->assertSame('r2', $routes->getName($r2));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotAddSameRouteTwice()
    {
        $routes = new RouteCollection();
        $routes->add('a1', new Route('/a1'));
        $routes->add('a1', new Route('/aaa1'));
    }

    public function testMergeCollectionAndOverrideRoutes()
    {
        $routes1 = new RouteCollection();
        $routes1->add('a1', new Route('/a1'));
        $routes1->add('a2', new Route('/a2'));
        $routes1->add('a3', new Route('/a3'));

        $routes2 = new RouteCollection();
        $routes2->add('a1', new Route('/aaa1'));
        $routes2->add('b2', new Route('/b2'));

        $routes1->merge($routes2, true);

        $this->assertNull($routes1->match('/a1'));
        $this->assertInstanceOf(Route::class, $routes1->match('/aaa1'));
        $this->assertInstanceOf(Route::class, $routes1->match('/a2'));
        $this->assertInstanceOf(Route::class, $routes1->match('/a3'));
        $this->assertInstanceOf(Route::class, $routes1->match('/b2'));
        $this->assertCount(4, $routes1->getRoutes());
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage A routes collection cannot merge into itself.
     */
    public function testCollectionCannotMergeItself()
    {
        $routes1 = new RouteCollection();
        $routes1->merge($routes1);
    }

    public function testMergeSeveralCollections()
    {
        $routes1 = new RouteCollection();
        $routes1->add('a1', new Route('/a1'));
        $routes1->add('a2', new Route('/a2'));
        $routes1->add('a3', new Route('/a3'));

        $routes2 = new RouteCollection();
        $routes2->add('b1', new Route('/b1'));
        $routes2->add('b2', new Route('/b2'));

        $routes3 = new RouteCollection();
        $routes3->add('c1', new Route('/c1'));

        $this->assertCount(3, $routes1);

        $routes1->merge($routes2);
        $routes1->merge($routes3);

        $this->assertCount(6, $routes1);
        $this->assertCount(2, $routes2);
        $this->assertCount(1, $routes3);
    }
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

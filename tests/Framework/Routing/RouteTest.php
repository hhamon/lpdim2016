<?php

namespace Test\Framework\Routing;

use Framework\Routing\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testGetParametersWithComplexMatch()
    {
        $route = new Route(
            '/article/{year}/{month}/{id}-{page}.html',
            ['controller' => 'Application\Controller\ShowArticleAction'],
            ['GET'],
            [
                'id' => '\d+',
                'year' => '20\d{2}',
                'month' => '0[1-9]|1[0-2]',
                'page' => '\d+',
            ]
        );

        $this->assertTrue($route->match('/article/2015/01/12345-12.html'));
        $this->assertSame(
            [
                'controller' => 'Application\Controller\ShowArticleAction',
                'year' => '2015',
                'month' => '01',
                'id' => '12345',
                'page' => '12',
            ],
            $route->getParameters()
        );
    }

    public function testComplexRouteMatch()
    {
        $route = new Route(
            '/article/{year}/{month}/{id}-{page}.html',
            ['controller' => 'Application\Controller\ShowArticleAction'],
            ['GET'],
            [
                'id' => '\d+',
                'year' => '20\d{2}',
                'month' => '0[1-9]|1[0-2]',
                'page' => '\d+',
            ]
        );

        $this->assertTrue($route->match('/article/2015/01/122222-1.html'));
        $this->assertTrue($route->match('/article/2015/12/1-1.html'));
        $this->assertTrue($route->match('/article/2015/03/63587376396-18327.html'));
        $this->assertTrue($route->match('/article/2018/01/1-2.html'));
        $this->assertFalse($route->match('/article/2222/10/122222-1.html'));
        $this->assertFalse($route->match('/article/2016/00/42-5.html'));
        $this->assertFalse($route->match('/article/2016/13/42-5.html'));
    }

    public function testSimpleRouteMatch()
    {
        $route = new Route('/home');

        $this->assertTrue($route->match('/home'));
        $this->assertFalse($route->match('/home/'));
        $this->assertFalse($route->match('/hom'));
        $this->assertFalse($route->match('/'));
    }

    public function testCreateRoute()
    {
        $route = new Route(
            '/article/{id}.html',
            [ 'controller' => 'Foo\Bar'],
            [ 'GET', 'POST' ],
            [ 'id' => '\d+' ]
        );

        $this->assertSame('/article/{id}.html', $route->getPath());
        $this->assertSame([ 'controller' => 'Foo\Bar'], $route->getParameters());
        $this->assertSame([ 'GET', 'POST', 'HEAD' ], $route->getMethods());
        $this->assertSame([ 'id' => '\d+' ], $route->getRequirements());
        $this->assertSame('\d+', $route->getRequirement('id'));
        $this->assertTrue($route->matchRequirement('id', '3'));
        $this->assertTrue($route->matchRequirement('id', '32'));
        $this->assertFalse($route->matchRequirement('id', 'foo'));
        $this->assertSame(['id'], $route->getPathTokens());
    }
}

<?php

namespace tests\Framework\Routing;

use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\MethodNotAllowedException;
use Framework\Routing\RequestContext;
use Framework\Routing\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Framework\Routing\MethodNotAllowedException
     */
    public function testMethodNotAllowed()
    {
        $router = $this->createRouter();
        $router->match(new RequestContext('POST', '/home'));
    }

    /**
     * @expectedException \Framework\Routing\RouteNotFoundException
     */
    public function testNoMatchingRoute()
    {
        $router = $this->createRouter();
        $router->match(new RequestContext('GET', '/unknown'));
    }

    public function testMatchRoute()
    {
        $router = $this->createRouter();

        $this->assertSame(
            [ '_route' => 'login' ],
            $router->match(new RequestContext('GET', '/login'))
        );

        $this->assertSame(
            [ '_route' => 'home', '_controller' => 'Application\Controller\HomeAction' ],
            $router->match(new RequestContext('GET', '/home'))
        );
    }

    private function createRouter()
    {
        return new Router(__DIR__.'/Fixtures/routes.php', new PhpFileLoader());
    }
}

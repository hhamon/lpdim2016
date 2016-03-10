<?php

namespace Tests\Framework;

use Framework\Http\Request;
use Framework\KernelEvent;
use Framework\RouterListener;
use Framework\Routing\Loader\LazyFileLoader;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\RequestContext;
use Framework\Routing\RequestContextAwareInterface;
use Framework\Routing\Router;
use Framework\Routing\UrlGeneratorInterface;

class RouterListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnKernelRequest()
    {
        $request = new Request('GET', '/home', 'HTTP', '1.1');
        $event = new KernelEvent($request);

        $router = new Router(new LazyFileLoader(__DIR__.'/Routing/Fixtures/routes.php', new PhpFileLoader()));

        $urlGenerator = $this->getMock(RequestContextAwareInterface::class);
        $urlGenerator
            ->expects($this->once())
            ->method('setRequestContext')
            ->with($this->isInstanceOf(RequestContext::class))
        ;

        $listener = new RouterListener($router, $urlGenerator);
        $listener->onKernelRequest($event);

        $this->assertSame($request, $event->getRequest());
        $this->assertNull($event->getResponse());
        $this->assertFalse($event->hasResponse());
        $this->assertSame(
            [ '_route' => 'home', '_controller' => 'Application\Controller\HomeAction' ],
            $request->getAttributes()
        );
    }
}

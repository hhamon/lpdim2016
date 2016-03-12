<?php

namespace Tests\Framework;

use Framework\Http\Request;
use Framework\KernelEvent;
use Framework\RouterListener;
use Framework\Routing\Loader\PhpFileLoader;
use Framework\Routing\Router;

class RouterListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testOnKernelRequest()
    {
        $request = new Request('GET', '/home', 'HTTP', '1.1');
        $event = new KernelEvent($request);

        $listener = new RouterListener(new Router(
            __DIR__.'/Routing/Fixtures/routes.php',
            new PhpFileLoader()
        ));

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

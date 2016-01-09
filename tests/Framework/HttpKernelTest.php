<?php

namespace Tests\Framework;

use Framework\ClosureControllerFactory;
use Framework\EventManager\EventManager;
use Framework\ExceptionEvent;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\HttpKernel;
use Framework\KernelEvent;
use Framework\KernelEvents;

class HttpKernelTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEarlyResponse()
    {
        $em = new EventManager();
        $em->addEventListener(KernelEvents::REQUEST, function (KernelEvent $event) {
            $event->setResponse(Response::createFromRequest($event->getRequest(), 'EARLY'));
        });

        $kernel = new HttpKernel($em, new ClosureControllerFactory());
        $response = $kernel->handle(Request::create('GET', '/home'));

        $this->assertSame('EARLY', $response->getBody());
    }

    public function testGetResponseForRequest()
    {
        $request = Request::create('GET', '/home');
        $request->setAttribute('_controller', function (Request $request) {
            return Response::createFromRequest($request, 'WIN!!!');
        });

        $em = new EventManager();
        $em->addEventListener(KernelEvents::RESPONSE, function (KernelEvent $event) {
            $response = $event->getResponse();
            $response->addHeader('X-API-Token', 'super-token');
        });
        
        $kernel = new HttpKernel($em, new ClosureControllerFactory());
        $response = $kernel->handle($request);

        $this->assertSame('WIN!!!', $response->getBody());
        $this->assertSame('super-token', $response->getHeader('x-api-token'));
    }

    public function testControllerDidNotReturnResponse()
    {
        $em = new EventManager();
        $em->addEventListener(KernelEvents::EXCEPTION, function (ExceptionEvent $event) {
            $event->setResponse(Response::createFromRequest(
                $event->getRequest(),
                $event->getException()->getMessage()
            ));
        });

        $request = Request::create('GET', '/home');
        $request->setAttribute('_controller', function () {
            return 'foo';
        });

        $kernel = new HttpKernel($em, new ClosureControllerFactory());
        $response = $kernel->handle($request);

        $this->assertSame(
            'A controller must return a Response object.',
            $response->getBody()
        );
    }

    public function testGetResponseForException()
    {
        $em = new EventManager();
        $em->addEventListener(KernelEvents::EXCEPTION, function (ExceptionEvent $event) {
            $event->setResponse(Response::createFromRequest(
                $event->getRequest(),
                $event->getException()->getMessage()
            ));
        });

        $kernel = new HttpKernel($em, new ClosureControllerFactory());
        $response = $kernel->handle(Request::create('GET', '/home'));

        $this->assertSame('NO CONTROLLER FOUND!', $response->getBody());
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNoGeneratedResponseForException()
    {
        $em = new EventManager();
        $em->addEventListener(KernelEvents::EXCEPTION, function (ExceptionEvent $event) {
            // do not generate a response!
        });

        $kernel = new HttpKernel($em, new ClosureControllerFactory());
        $kernel->handle(Request::create('GET', '/home'));
    }
}

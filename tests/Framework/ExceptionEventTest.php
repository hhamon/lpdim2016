<?php

namespace Tests\Framework;

use Framework\ExceptionEvent;
use Framework\Http\Request;
use Framework\Http\Response;

class ExceptionEventTest extends \PHPUnit_Framework_TestCase
{
    public function testStopExceptionEvent()
    {
        $request = new Request('GET', '/home', 'HTTP', '1.1');
        $response = Response::createFromRequest($request, '', 200);
        $exception = new \Exception();

        $event = new ExceptionEvent($exception, $request);

        $this->assertSame($exception, $event->getException());
        $this->assertSame($request, $event->getRequest());
        $this->assertNull($event->getResponse());
        $this->assertFalse($event->hasResponse());
        $this->assertFalse($event->isStopped());

        $event->setResponse($response);

        $this->assertTrue($event->hasResponse());
        $this->assertSame($response, $event->getResponse());
        $this->assertTrue($event->isStopped());
    }
}

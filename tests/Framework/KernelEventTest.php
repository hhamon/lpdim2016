<?php

namespace Tests\Framework;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\KernelEvent;

class KernelEventTest extends \PHPUnit_Framework_TestCase
{
    public function testStopRequestEvent()
    {
        $request = new Request('GET', '/home', 'HTTP', '1.1');
        $response = Response::createFromRequest($request, '', 200);

        $event = new KernelEvent($request);

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

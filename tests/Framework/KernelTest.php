<?php

namespace Tests\Framework;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Kernel;
use Framework\ServiceLocator\ServiceLocator;

class KernelTest extends \PHPUnit_Framework_TestCase
{
    public function testHandleRequest()
    {
        $dic = new ServiceLocator();
        $dic->register('http_kernel', function () {
            return new DummyHttpKernel();
        });

        $kernel = new Kernel($dic);
        $response = $kernel->handle(Request::create('GET', '/'));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('DUMMY!', $response->getBody());
    }
}

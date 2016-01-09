<?php

namespace tests\Framework\Routing;

use Framework\Routing\MethodNotAllowedException;

class MethodNotAllowedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateException()
    {
        $e = new MethodNotAllowedException('POST', ['HEAD', 'GET']);

        $this->assertSame('POST', $e->getMethod());
        $this->assertSame(['HEAD', 'GET'], $e->getAllowedMethods());

    }
}

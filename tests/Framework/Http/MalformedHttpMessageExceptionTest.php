<?php

namespace Tests\Framework\Http;

use Framework\Http\MalformedHttpMessageException;

class MalformedHttpMessageExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetHttpMessage()
    {
        $e = new MalformedHttpMessageException('foo');

        $this->assertSame('foo', $e->getHttpMessage());
    }
}

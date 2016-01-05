<?php

namespace Tests\Framework\Http;

use Framework\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateRequestInstance()
    {
        $request = new Request(Request::GET, '/', Request::HTTP, '1.1');

        $this->assertSame(Request::GET, $request->getMethod());
        $this->assertSame('/', $request->getPath());
        $this->assertSame(Request::HTTP, $request->getScheme());
        $this->assertSame('1.1', $request->getSchemeVersion());
        $this->assertEmpty($request->getHeaders());
        $this->assertEmpty($request->getBody());
    }
}

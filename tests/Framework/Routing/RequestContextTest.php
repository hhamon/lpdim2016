<?php

namespace Test\Framework\Routing;

use Framework\Http\Request;
use Framework\Routing\RequestContext;

class RequestContextTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromRequest()
    {
        $request = new Request('GET', '/home', 'HTTP', '1.1');
        $context = RequestContext::createFromRequest($request);

        $this->assertSame('GET', $context->getMethod());
        $this->assertSame('/home', $context->getPath());
        $this->assertSame('GET /home', (string) $context);
    }
}

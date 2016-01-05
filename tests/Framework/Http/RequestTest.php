<?php

namespace Tests\Framework\Http;

use Framework\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidHttpMethod
     */
    public function testUnsupportedHttpMethod($method)
    {
        new Request($method, '/', 'HTTP', '1.1');
    }

    public function provideInvalidHttpMethod()
    {
        return [
            [ 'FOO' ],
            [ 'BAR' ],
            [ 'BAZ' ],
            [ 'PURGE' ],
            [ 'TOTO' ],
        ];
    }

    /**
     * @dataProvider provideRequestParameters
     */
    public function testCreateRequestInstance($method, $path)
    {
        $request = new Request($method, $path, Request::HTTP, '1.1');

        $this->assertSame($method, $request->getMethod());
        $this->assertSame($path, $request->getPath());
        $this->assertSame(Request::HTTP, $request->getScheme());
        $this->assertSame('1.1', $request->getSchemeVersion());
        $this->assertEmpty($request->getHeaders());
        $this->assertEmpty($request->getBody());
    }

    public function provideRequestParameters()
    {
        return [
            [ Request::GET,     '/'              ],
            [ Request::POST,    '/home'          ],
            [ Request::PUT,     '/foo'           ],
            [ Request::PATCH,   '/bar'           ],
            [ Request::OPTIONS, '/options'       ],
            [ Request::CONNECT, '/lol'           ],
            [ Request::TRACE,   '/contact'       ],
            [ Request::HEAD,    '/fr/article/42' ],
            [ Request::DELETE,  '/cgv'           ],
        ];
    }
}

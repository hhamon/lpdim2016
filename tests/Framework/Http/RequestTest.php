<?php

namespace Tests\Framework\Http;

use Framework\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromMessage()
    {
        $message = <<<MESSAGE
GET /home HTTP/1.1
host: http://wikipedia.com
user-agent: Mozilla/Firefox
content-type: application/json

{ "foo": "bar" }
MESSAGE;

        $request = Request::createFromMessage($message);

        $this->assertInstanceOf(Request::class, $request);
        $this->assertSame($message, $request->getMessage());
        $this->assertSame($message, (string) $request);
    }

    public function testGetMessage()
    {
        $message = <<<MESSAGE
GET /home HTTP/1.1
host: http://wikipedia.com
user-agent: Mozilla/Firefox
content-type: application/json

{ "foo": "bar" }
MESSAGE;

        $body = '{ "foo": "bar" }';
        $headers = [
            'Host' => 'http://wikipedia.com',
            'User-Agent' => 'Mozilla/Firefox',
            'Content-Type' => 'application/json',
        ];

        $request = new Request('GET', '/home', 'HTTP', '1.1', $headers, $body);

        $this->assertSame($message, $request->getMessage());
        $this->assertSame($message, (string) $request);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testAddSameHttpHeaderTwice()
    {
        $headers = [
            'Content-Type' => 'text/xml',
            'CONTENT-TYPE' => 'application/json',
        ];

        new Request('GET', '/', 'HTTP', '1.1', $headers);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidHttpSchemeVersion
     */
    public function testUnsupportedHttpSchemeVersion($version)
    {
        new Request('GET', '/', 'HTTP', $version);
    }

    public function provideInvalidHttpSchemeVersion()
    {
        return [
            [ '0.1' ],
            [ '0.5' ],
            [ '1.2' ],
            [ '1.5' ],
            [ '2.1' ],
        ];
    }

    /**
     * @dataProvider provideValidHttpSchemeVersion
     */
    public function testSupportedHttpSchemeVersion($version)
    {
        new Request('GET', '/', 'HTTP', $version);
    }

    public function provideValidHttpSchemeVersion()
    {
        return [
            [ Request::VERSION_1_0 ],
            [ Request::VERSION_1_1 ],
            [ Request::VERSION_2_0 ],
        ];
    }

    /**
     * @dataProvider provideValidHttpScheme
     */
    public function testSupportedHttpScheme($scheme)
    {
        new Request('GET', '/', $scheme, '1.1');
    }

    public function provideValidHttpScheme()
    {
        return [
            [ Request::HTTP ],
            [ Request::HTTPS ],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidHttpScheme
     */
    public function testUnsupportedHttpScheme($scheme)
    {
        new Request('GET', '/', $scheme, '1.1');
    }

    public function provideInvalidHttpScheme()
    {
        return [
            [ 'FTP' ],
            [ 'SFTP' ],
            [ 'SSH' ],
        ];
    }

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
        $body = '{ "foo": "bar" }';
        $headers = [
            'Host' => 'http://wikipedia.com',
            'User-Agent' => 'Mozilla/Firefox',
            'Content-Type' => 'application/json',
        ];

        $request = new Request($method, $path, Request::HTTP, '1.1', $headers, $body);

        $this->assertSame($method, $request->getMethod());
        $this->assertSame($path, $request->getPath());
        $this->assertSame(Request::HTTP, $request->getScheme());
        $this->assertSame('1.1', $request->getSchemeVersion());
        $this->assertSame($body, $request->getBody());

        $this->assertCount(3, $request->getHeaders());

        $this->assertSame(
            [
                'host' => 'http://wikipedia.com',
                'user-agent' => 'Mozilla/Firefox',
                'content-type' => 'application/json',
            ],
            $request->getHeaders()
        );

        $this->assertSame('http://wikipedia.com', $request->getHeader('Host'));
        $this->assertSame('Mozilla/Firefox', $request->getHeader('User-Agent'));
        $this->assertSame('application/json', $request->getHeader('Content-Type'));
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

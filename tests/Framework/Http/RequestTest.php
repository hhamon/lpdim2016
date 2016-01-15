<?php

namespace Tests\Framework\Http;

use Framework\Http\Request;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetMethod()
    {
        $_SERVER['PATH_INFO'] = '/';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['page'] = '2';
        $_POST['foo'] = 'bar';
        $_COOKIE['name'] = 'thomas';

        $request = Request::createFromGlobals();
        $request->setAttribute('route', 'homepage');

        $this->assertSame('homepage', $request->get('route'));
        $this->assertSame('2', $request->get('page'));
        $this->assertSame('bar', $request->get('foo'));
        $this->assertSame('thomas', $request->get('name'));
        $this->assertSame('none', $request->get('null', 'none'));
        $this->assertNull($request->get('hello'));
    }

    public function testGetCookieParameter()
    {
        $_SERVER['PATH_INFO'] = '/';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET[] = $_COOKIE = $_POST = [];
        $_COOKIE['name'] = 'hugo';

        $request = Request::createFromGlobals();

        $this->assertSame('hugo', $request->getCookie('name'));
        $this->assertNull($request->getCookie('foobar'));
        $this->assertSame('john', $request->getCookie('foobar', 'john'));
        $this->assertSame('hugo', $request->get('name'));
        $this->assertSame('john', $request->get('foobar', 'john'));
    }

    public function testGetRequestParameter()
    {
        $_SERVER['PATH_INFO'] = '/';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET[] = $_COOKIE = $_POST = [];
        $_POST['title'] = 'My Title';

        $request = Request::createFromGlobals();

        $this->assertSame('My Title', $request->getRequestParameter('title'));
        $this->assertNull($request->getQueryParameter('foobar'));
        $this->assertSame('Your Title', $request->getRequestParameter('foobar', 'Your Title'));
        $this->assertSame('My Title', $request->get('title'));
        $this->assertSame('Your Title', $request->get('foobar', 'Your Title'));
    }

    public function testGetQueryStringParameter()
    {
        $_SERVER['PATH_INFO'] = '/';
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET[] = $_COOKIE = $_POST = [];
        $_GET['page'] = '2';

        $request = Request::createFromGlobals();

        $this->assertSame('2', $request->getQueryParameter('page'));
        $this->assertNull($request->getQueryParameter('toto'));
        $this->assertSame(1, $request->getQueryParameter('toto', 1));
        $this->assertSame('2', $request->get('page'));
        $this->assertSame(1, $request->get('toto', 1));
    }

    /**
     * @expectedException \Framework\Http\MalformedHttpHeaderException
     */
    public function testHeadersAreMalformed()
    {
        $message = <<<MESSAGE
GET /home HTTP/1.1
user-agent: Mozilla/Firefox
content-type: application/json
foo bar: http://wikipedia.com

{ "foo": "bar" }
MESSAGE;

        Request::createFromMessage($message);
    }

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
        $this->assertNull($request->getHeader('foo'));
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

    public function testRequestAttributes()
    {
        $message = <<<MESSAGE
GET /home HTTP/1.1

MESSAGE;

        $request = Request::createFromMessage($message);
        $request->setAttributes(['foo' => 'bar']);
        $request->setAttribute('hello', 'world');

        $this->assertTrue($request->hasAttribute('hello'));
        $this->assertSame('world', $request->getAttribute('hello'));
        $this->assertTrue($request->hasAttribute('foo'));
        $this->assertSame('bar', $request->getAttribute('foo'));
        $this->assertFalse($request->hasAttribute('bar'));
        $this->assertSame('qux', $request->getAttribute('bar', 'qux'));
    }

    /**
     * @expectedException \Framework\Http\MalformedHttpMessageException
     * @dataProvider provideInvalidMessage
     */
    public function testUnableToParseMesage($message)
    {
        Request::createFromMessage($message);
    }

    public function provideInvalidMessage()
    {
        return [
            ['foooooo'],
            [''],
            [true],
            [false],
            [null],
            [10],
        ];
    }

    /**
     * @dataProvider provideGlobalInformation
     */
    public function testCreateFromGlobals($path, $expectedPath)
    {
        $_SERVER['PATH_INFO'] = $path;
        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $request = Request::createFromGlobals();

        $this->assertSame($expectedPath, $request->getPath());
        $this->assertSame('HTTP', $request->getScheme());
        $this->assertSame('1.1', $request->getSchemeVersion());
        $this->assertSame('POST', $request->getMethod());
    }

    public function provideGlobalInformation()
    {
        return [
            ['', '/'],
            ['/home', '/home'],
        ];
    }
}

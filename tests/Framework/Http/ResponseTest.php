<?php

namespace Tests\Framework\Http;

use Framework\Http\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromMessage()
    {
        $message = <<<MESSAGE
HTTP/1.1 404 Page Not Found
content-type: text/html
cache-control: no-cache

<p>
    Sorry, this page does not exist!
</p>
MESSAGE;

        $response = Response::createFromMessage($message);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Page Not Found', $response->getReasonPhrase());
        $this->assertCount(2, $response->getHeaders());
        $this->assertSame('text/html', $response->getHeader('content-type'));
        $this->assertSame('no-cache', $response->getHeader('cache-control'));
        $this->assertSame('HTTP', $response->getScheme());
        $this->assertSame('1.1', $response->getSchemeVersion());
        $this->assertContains('page does not', $response->getBody());
        $this->assertSame($message, $response->getMessage());
        $this->assertSame($message, (string) $response);
    }

    /**
     * @expectedException \Framework\Http\MalformedHttpMessageException
     * @dataProvider provideInvalidMessage
     */
    public function testUnableToParseMesage($message)
    {
        Response::createFromMessage($message);
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
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidStatusCode
     */
    public function testInvalidResponseStatusCode($statusCode)
    {
        new Response($statusCode, 'HTTP','1.1');
    }

    public function provideInvalidStatusCode()
    {
        return [
            [ 0 ],
            [ 10 ],
            [ 99 ],
            [ 600 ],
            [ 1000 ],
        ];
    }
}

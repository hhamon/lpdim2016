<?php

namespace Tests\Framework\Http;

use Framework\Http\RedirectResponse;

class RedirectResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @dataProvider provideInvalidStatusCode
     */
    public function testInvalidResponseStatusCode($statusCode)
    {
        new RedirectResponse('http://foo.tld', $statusCode);
    }
        
    public function provideInvalidStatusCode()
    {
        return [
            [ 100 ],
            [ 200 ],
            [ 309 ],
            [ 400 ],
            [ 500 ],
        ];
    }
}

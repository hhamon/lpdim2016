<?php

namespace Test\Framework\Http;

use Framework\Http\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidHeaderName()
    {
        new Header(true, 'foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidHeaderValue()
    {
        new Header('Accept', true);
    }

    /**
     * @expectedException \Framework\Http\MalformedHttpHeaderException
     */
    public function testCannotCreateFromString()
    {
        Header::createFromString('foo');
    }

    public function testCreateFromString()
    {
        $header = Header::createFromString('Accept-Language: en;q=0.3, fr;q=1.0');

        $this->assertSame('accept-language', $header->getName());
        $this->assertSame('en;q=0.3, fr;q=1.0', $header->getValue());
        $this->assertTrue($header->match('Accept-Language'));
        $this->assertSame('accept-language: en;q=0.3, fr;q=1.0', (string) $header);
        $this->assertSame(['accept-language' => 'en;q=0.3, fr;q=1.0'], $header->toArray());
    }
}

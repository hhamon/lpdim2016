<?php

namespace tests\Framework\ServiceLocator;

use Framework\ServiceLocator\ServiceLocator;

class ServiceLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSameServiceTwice()
    {
        $dic = new ServiceLocator();
        $dic->register('foo', function () {
            return new \stdClass();
        });

        $service1 = $dic->getService('foo');
        $service2 = $dic->getService('foo');

        $this->assertSame($service1, $service2);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotGetUnregisteredService()
    {
        $dic = new ServiceLocator();
        $dic->getService('foo');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotRegisterServiceWithInvalidName()
    {
        $dic = new ServiceLocator();
        $dic->register(1, function () {
            // ...
        });
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotSetInvalidParameter()
    {
        $dic = new ServiceLocator();
        $dic->setParameter(1, 'bar');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCannotGetUndefinedParameter()
    {
        $dic = new ServiceLocator();
        $dic->getParameter('foo');
    }

    public function testGetParameter()
    {
        $dic = new ServiceLocator(['foo' => 'bar']);

        $this->assertSame('bar', $dic->getParameter('foo'));
    }

    public function testGetService()
    {
        $dic = new ServiceLocator();
        $dic->register('foo', function () {
            return new \stdClass();
        });

        $this->assertInstanceOf('stdClass', $dic->getService('foo'));
    }
}

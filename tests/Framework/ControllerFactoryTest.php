<?php

namespace tests\Framework;

use Framework\ControllerFactory;

class ControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testControllerClassDoesNotExist()
    {
        $factory = new ControllerFactory();
        $factory->createController([ '_controller' => 'FOOOOOOOOOOOOOO' ]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testControllerNameIsNotDefined()
    {
        $factory = new ControllerFactory();
        $factory->createController([ 'foo' => 'bar' ]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCreateNotInvokableController()
    {
        $factory = new ControllerFactory();
        $factory->createController([ '_controller' => 'stdClass' ]);
    }

    public function testCreateController()
    {
        $factory = new ControllerFactory();

        $this->assertInstanceOf(FooBar::class, $factory->createController([ '_controller' => FooBar::class ]));
    }
}

class FooBar
{
    public function __invoke()
    {
        
    }
}

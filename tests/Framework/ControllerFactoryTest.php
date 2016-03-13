<?php

namespace Test\Framework;

use Framework\ControllerFactory;
use Framework\DefaultControllerNameParser;
use Tests\Framework\Fixtures\Controller\DemoAction;

class ControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \RuntimeException
     */
    public function testControllerClassDoesNotExist()
    {
        $factory = new ControllerFactory(new DefaultControllerNameParser());
        $factory->createController([ '_controller' => 'FOOOOOOOOOOOOOO' ]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testControllerNameIsNotDefined()
    {
        $factory = new ControllerFactory(new DefaultControllerNameParser());
        $factory->createController([ 'foo' => 'bar' ]);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCreateNotInvokableController()
    {
        $factory = new ControllerFactory(new DefaultControllerNameParser());
        $factory->createController([ '_controller' => 'stdClass' ]);
    }

    public function testCreateController()
    {
        $factory = new ControllerFactory(new DefaultControllerNameParser());

        $this->assertInstanceOf(DemoAction::class, $factory->createController([ '_controller' => DemoAction::class ]));
    }
}

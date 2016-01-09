<?php

namespace Tests\Framework;


use Framework\AbstractAction;
use Framework\ControllerEvent;
use Framework\ControllerListener;
use Framework\Http\Request;
use Framework\ServiceLocator\ServiceLocatorInterface;

class ControllerListenerTest extends \PHPUnit_Framework_TestCase
{
    public function testServiceLocatorIsInjected()
    {
        $dic = $this->getMock(ServiceLocatorInterface::class);

        $action = $this->getMock(SuperListenerAction::class);
        $action->expects($this->once())->method('setServiceLocator')->with($dic);

        $event = new ControllerEvent($action, new Request('GET', '/', 'HTTP', '1.1'));
        $listener = new ControllerListener($dic);
        $listener->onKernelController($event);
    }

    public function testServiceLocatorIsNotInjected()
    {
        $action = $this->getMock(ListenerAction::class);
        $action->expects($this->never())->method('setServiceLocator');

        $dic = $this->getMock(ServiceLocatorInterface::class);

        $event = new ControllerEvent($action, new Request('GET', '/', 'HTTP', '1.1'));
        $listener = new ControllerListener($dic);
        $listener->onKernelController($event);
    }
}

class ListenerAction
{
    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }
}

class SuperListenerAction extends AbstractAction
{
    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }
}

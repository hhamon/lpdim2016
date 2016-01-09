<?php

namespace Tests\Framework;

use Framework\ControllerEvent;
use Framework\Http\Request;

class ControllerEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideCallable
     */
    public function testCreateControllerEvent($callable)
    {
        $request = new Request('GET', '/home', 'HTTP', '1.1');

        $event = new ControllerEvent($callable, $request);

        $this->assertSame($callable, $event->getController());
        $this->assertSame($request, $event->getRequest());
    }

    public function provideCallable()
    {
        return [
            //[ 'some_callback' ],
            [ [ new Controller(), 'doSomething' ] ],
            [ [ Controller::class, 'doAnotherThing' ] ],
            [ function (ControllerEvent $event) { } ],
            [ new Controller() ],
        ];
    }
}

class Controller
{
    public static function doAnotherThing(ControllerEvent $event)
    {

    }

    public function doSomething(ControllerEvent $event)
    {
        
    }

    public function __invoke(ControllerEvent $event)
    {
    }
}

function some_callback(ControllerEvent $event)
{
    
}

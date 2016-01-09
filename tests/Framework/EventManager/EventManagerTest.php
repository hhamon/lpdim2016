<?php

namespace Tests\Framework\EventManager;

use Framework\EventManager\EventManager;

class EventManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testDispatchEventWithPriorityOrderForNotifiedListeners()
    {
        $l1 = new DemoEventListener('A');
        $l2 = new DemoEventListener('B');
        $l3 = new DemoEventListener('C');
        $l4 = new DemoEventListener('D');

        $dispatcher = new EventManager();
        $dispatcher->addEventListener('demo.event', $l1, 10);
        $dispatcher->addEventListener('demo.event', $l2, 50);
        $dispatcher->addEventListener('demo.event', $l3, 300);
        $dispatcher->addEventListener('demo.event', $l4, 70);
        $event = $dispatcher->dispatch('demo.event', new DemoEvent('DEMO'));

        // Define expected listeners stack to check for priorities
        $stack = [ $l3, $l4, $l2, $l1 ];
        
        // Check event state
        $this->assertFalse($event->isStopped());
        $this->assertSame('DEMO', $event->getState());
        $this->assertCount(4, $event->getStackedListeners());
        $this->assertSame($stack, $event->getStackedListeners());

        // Check listeners state
        $this->assertTrue($l1->isCalled());
        $this->assertTrue($l1->isCalled());
        $this->assertTrue($l3->isCalled());
        $this->assertTrue($l4->isCalled());
    }

    public function testDispatchEventWithNoAttachedListeners()
    {
        $dispatcher = new EventManager();
        $event = $dispatcher->dispatch('demo.event', new DemoEvent('DEMO'));

        $this->assertFalse($event->isStopped());
        $this->assertSame('DEMO', $event->getState());
        $this->assertCount(0, $event->getStackedListeners());
    }

    public function testDispatchEventAndNotifyAllListeners()
    {
        $l1 = new DemoEventListener('A');
        $l2 = new DemoEventListener('B');
        $l3 = new DemoEventListener('C');
        $l4 = new DemoEventListener('D');

        $event = new DemoEvent('DEMO');

        $dispatcher = new EventManager();
        $dispatcher->addEventListener('demo.event', $l1);
        $dispatcher->addEventListener('demo.event', $l2);
        $dispatcher->addEventListener('demo.event', $l3);
        $dispatcher->addEventListener('other.event', $l4);

        $result = $dispatcher->dispatch('demo.event', $event);

        // Check event state
        $this->assertSame($result, $event);
        $this->assertFalse($event->isStopped());
        $this->assertSame('DEMO', $event->getState());
        $this->assertCount(3, $event->getStackedListeners());
        $this->assertContains($l1, $event->getStackedListeners());
        $this->assertContains($l2, $event->getStackedListeners());
        $this->assertContains($l3, $event->getStackedListeners());
        $this->assertNotContains($l4, $event->getStackedListeners());

        // Check listeners state
        $this->assertTrue($l1->isCalled());
        $this->assertTrue($l1->isCalled());
        $this->assertTrue($l3->isCalled());
        $this->assertFalse($l4->isCalled());
    }

    public function testEventPropagationIsStopped()
    {
        $l1 = new DemoEventListener('A');
        $l2 = new DemoEventListener('B', true);
        $l3 = new DemoEventListener('C');
        $l4 = new DemoEventListener('D');

        $event = new DemoEvent('DEMO');

        $dispatcher = new EventManager();
        $dispatcher->addEventListener('demo.event', $l1);
        $dispatcher->addEventListener('demo.event', $l2);
        $dispatcher->addEventListener('demo.event', $l3);
        $dispatcher->addEventListener('other.event', $l4);

        $result = $dispatcher->dispatch('demo.event', $event);

        // Check event state
        $this->assertSame($result, $event);
        $this->assertTrue($event->isStopped());
        $this->assertSame('B', $event->getState());
        $this->assertCount(2, $event->getStackedListeners());
        $this->assertContains($l1, $event->getStackedListeners());
        $this->assertContains($l2, $event->getStackedListeners());
        $this->assertNotContains($l3, $event->getStackedListeners());
        $this->assertNotContains($l4, $event->getStackedListeners());

        // Check listeners state
        $this->assertTrue($l1->isCalled());
        $this->assertTrue($l1->isCalled());
        $this->assertFalse($l3->isCalled());
        $this->assertFalse($l4->isCalled());
    }
}

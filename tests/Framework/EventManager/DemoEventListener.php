<?php

namespace Tests\Framework\EventManager;

class DemoEventListener
{
    private $name;
    private $called;
    private $stopEvent;

    public function __construct($name, $stopEvent = false)
    {
        $this->name = $name;
        $this->called = false;
        $this->stopEvent = $stopEvent;
    }

    public function isCalled()
    {
        return $this->called;
    }

    public function __invoke(DemoEvent $event)
    {
        $this->called = true;

        $event->stackListener($this);

        if ($this->stopEvent) {
            $event->resetState($this->name);
        }
    }
}

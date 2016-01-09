<?php

namespace Tests\Framework\EventManager;

use Framework\EventManager\Event;

class DemoEvent extends Event
{
    private $calledListeners;
    private $state;

    public function __construct($state)
    {
        parent::__construct();

        $this->state = $state;
        $this->calledListeners = [];
    }

    public function stackListener(callable $listener)
    {
        $this->calledListeners[] = $listener;
    }

    public function getStackedListeners()
    {
        return $this->calledListeners;
    }

    public function resetState($state)
    {
        $this->state = $state;
        $this->stop();
    }

    public function getState()
    {
        return $this->state;
    }
}


<?php

namespace Framework\EventManager;

abstract class Event
{
    private $stopped;

    public function __construct()
    {
        $this->stopped = false;
    }

    public function isStopped()
    {
        return $this->stopped;
    }

    public function stop()
    {
        $this->stopped = true;
    }
}

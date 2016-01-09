<?php

namespace Framework\EventManager;

interface EventManagerInterface
{
    /**
     * Dispatches an event.
     *
     * @param string $name  The event name
     * @param Event  $event The event object
     *
     * @return Event
     */
    public function dispatch($name, Event $event);

    /**
     * Attaches a new event listener to an event.
     *
     * @param string   $event    The name of the event to listen to
     * @param callable $listener The listener to register on this event
     * @param int      $priority The execution priority of this listener
     *
     * @return void
     */
    public function addEventListener($event, callable $listener, $priority = 0);
}

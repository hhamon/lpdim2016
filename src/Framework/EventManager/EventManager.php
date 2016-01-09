<?php

namespace Framework\EventManager;

class EventManager implements EventManagerInterface
{
    private $listeners;

    public function __construct()
    {
        $this->listeners = [];
    }

    /**
     * Dispatches an event.
     *
     * @param string $name  The event name
     * @param Event  $event The event object
     *
     * @return Event
     */
    public function dispatch($name, Event $event)
    {
        if (!$this->hasListeners($name)) {
            return $event;
        }

        foreach ($this->getSortedListeners($name) as $listener) {
            call_user_func_array($listener, [ $event ]);
            if ($event->isStopped()) {
                // Stop looping if a listener
                // has stopped the event propagation.
                break;
            }
        }

        return $event;
    }

    private function hasListeners($event)
    {
        return !empty($this->listeners[$event]);
    }

    private function getSortedListeners($event)
    {
        $listenersByPriority = [];
        foreach ($this->listeners[$event] as $listener) {
            $priority = $listener[1];
            $listenersByPriority[$priority][] = $listener[0];
        }

        krsort($listenersByPriority);

        $listeners = [];
        foreach ($listenersByPriority as $priorityListeners) {
            $listeners = array_merge($listeners, $priorityListeners);
        }

        return $listeners;
    }

    /**
     * Attaches a new event listener.
     *
     * @param string   $event    The name of the event to listen to
     * @param callable $listener The listener to register on this event
     * @param int      $priority The execution priority of this listener
     *
     * @return void
     */
    public function addEventListener($event, callable $listener, $priority = 0)
    {
        $this->listeners[$event][] = [ $listener, $priority ];
    }
}

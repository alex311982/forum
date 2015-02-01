<?php

/**
 * Abstract event dispatcher for observers
 *
 * @author Gubarev Alex
 */
abstract class Subject
{
    public $observers = array();

    /**
     * Attach observer to event
     *
     * @param string $eventName event name
     * @param string $observerName observer name
     * @return boolean true if added
     */
    abstract public function attach($eventName, $observerName);

    /**
     * Detach observer from event
     *
     * @param string $eventName event name
     * @param string $observerName observer name
     * @return boolean true if deleted
     */
    abstract public function detach($eventName, $observerName);

    /**
     * Fire event
     *
     * @param string $eventName event name
     * @param mixed $args event data
     * @return void
     */
    abstract public function dispatch($eventName, $args);
}
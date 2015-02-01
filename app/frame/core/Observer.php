<?php

/**
 * Abstract observer
 *
 * @author Gubarev Alex
 */
abstract class Observer
{
    /**
     * Update observer by event
     *
     * @param string $event event name
     * @return void
     */
    abstract public function update($event);
}
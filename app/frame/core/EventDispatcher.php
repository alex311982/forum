<?php

/**
 * Event dispatcher for observers
 *
 * @author Gubarev Alex
 */
class EventDispatcher extends Subject
{
    protected $data;
    protected $eventName;

    protected static $inst;

    /**
     * Call this method to get singleton
     *
     * @return EventDispatcher
     */
    public static function getInstance()
    {
        if (self::$inst === null) {
            self::$inst = new self();
        }
        return self::$inst;
    }

    /**
     * Attach observer to event
     *
     * @param string $eventName event name
     * @param string $observerName observer name
     * @return boolean true if added
     */
    public function attach($eventName, $observerName)
    {
        if (empty($eventName) || empty($observerName)) {
            return false;
        }
        if (Model::factory('Observer')->attach($eventName, $observerName)) {
            return true;
        }

        return false;
    }

    /**
     * Detach observer from event
     *
     * @param string $eventName event name
     * @param string $observerName observer name
     * @return boolean true if deleted
     */
    public function detach($eventName, $observerName)
    {
        if (empty($eventName) || empty($observerName)) {
            return false;
        }
        return Model::factory('Observer')->detach($eventName, $observerName);
    }

    /**
     * Return event data
     *
     * @return mixed event data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return event name
     *
     * @return string event name
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * Fire event
     *
     * @param string $eventName event name
     * @param mixed $args event data
     * @return void
     */
    public function dispatch($eventName, $args)
    {
        $this->eventName = $eventName;
        $this->data = $args;
        $this->notify();
    }

    /**
     * Set event name
     *
     * @param string $name event name
     * @return boolean
     */
    public function setEventName($name)
    {
        if (!empty($name)) {
            $this->eventName = $name;
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set event data
     *
     * @param mixed $data event data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Call attached to event observers
     *
     * @return void
     */
    public function notify()
    {
        $eventName = $this->getEventName();
        if (empty($this->observers[$eventName])) {
            $observerNames = Model::factory('Observer')->getByEventName($eventName);
            foreach ($observerNames as $observerName) {
                $this->observers[$eventName][] = new $observerName();
            }
        }
        if (!empty($this->observers[$eventName])) {
            foreach ($this->observers[$eventName] as $observer) {
                $observer->update($this);
            }
        }
    }

    /**
     * Construct of class
     *
     */
    private function __construct() {}

    /**
     * Magic method of class
     *
     */
    private function __clone() {}
}
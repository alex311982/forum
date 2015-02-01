<?php

/**
 * Observer table model
 *
 * @author Gubarev Alex
 */
final class ObserverTable extends ShopModel
{
    /**
     * Return names of attached observers
     *
     * @param string $eventName event name
     * @return array observers
     */
    public function getByEventName($eventName)
    {
        $observers = $this->db->quickSelectPlain('SELECT `observer_name` FROM `observers` WHERE `event_type` = "' . $eventName . '"', 'observer_name');

        if ($observers) {
            return $observers;
        }
        return array();
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
        $data = $this->db->insert(
            'INSERT INTO `observers` SET `event_type` = ?, `observer_name`=?',
            'ss',
            array($eventName, $observerName)
        );

        return (bool) $data;
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
        $data = $this->db->query(
            'DELETE FROM `observers` WHERE `event_type` = "' . $eventName . '" AND `observer_name`="' . $observerName . '"'
        );

        return $data;
    }
}
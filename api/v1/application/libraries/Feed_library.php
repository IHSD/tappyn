<?php defined("BASEPATH") or exit('No direct script access ALLOWED');

/**
 * Feed Library
 *
 * Class for generating and managing a users content feed. Whenever an event,
 * we store the event metadata in the pending_feed_events table. Every minute or
 * so, we then pull in all the pending events, and write their event data in the
 * feeds table, where we then pull an aggregated feed on behalff of the user
 */
class Feed_library {

    protected $table = 'feeds';
    protected $pend_table = 'pending_feed_events';

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Stash an event in the pending feeds table
     * @param  string  $event         Event that has occured
     * @param  string  $actor         Entity that created the action
     * @param  string  $verb          Action verb
     * @param  string  $object        Object type that was acted on
     * @param  string  $target        Where event was aimed?
     * @return boolean
     */
    public function createEvent($event, $actor, $verb, $object, $target = NULL)
    {
        $object = $this->_($object);

        return $this->db->insert($this->pend_table, array(
            'event' => $event,
            'actor' => $actor,
            'verb' => $verb,
            'object' => $object[0],
            'object_id' => $object[1],
            'target' => $target,
            'created' => time()
        ));
    }



    public function _($object)
    {
        return explode(':', $object);
    }
}

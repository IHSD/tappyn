<?php defined("BASEPATH") or exit('No direct script access allowed');

class Hook
{
    public function __construct()
    {
        parent::__construct();
    }

    static function trigger($event, $args)
    {
        error_log("Event {$event} triggered");
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Notification_library
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }
}

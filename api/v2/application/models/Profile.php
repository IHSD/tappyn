<?php defined("BASEPATH") or exit('No direct script access allowed');

class Profile extends MY_Model
{
    private static $db;
    private static $table = 'users';

    function __construct($data = NULL)
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    protected function save()
    {

    }
}

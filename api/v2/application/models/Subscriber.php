<?php defined("BASEPATH") or exit('No direct script access allowed');

class Subscriber extends MY_Model
{
    private static $db;
    private static $table = 'mailing_list';

    function __construct($data = NULL)
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    protected function save()
    {
        $this->data[SubscriberFields::CREATED_AT] = time();
        return self::$db->insert(self::$table, $this->data);
    }
}

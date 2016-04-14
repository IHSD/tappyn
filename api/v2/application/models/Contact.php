<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contact extends MY_Model
{
    private static $db;
    private static $table = 'contacts';

    function __construct($data = NULL)
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    protected function save()
    {
        $this->data[ContactFields::CREATED_AT] = time();
        if(self::$db->insert(self::$table, $this->data))
        {
            $this->data['id'] = self::$db->insert_id();
            return TRUE;
        }
        return FALSE;
    }
}

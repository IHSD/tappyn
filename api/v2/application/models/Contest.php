<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends MY_Model
{
    private static $db;
    private static $table = 'contests';

    function __construct($data = NULL)
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function all($params)
    {
        return self::$db->select('*')->from(self::$table)->limit(10)->get()->result('Contest');
    }

    static function get($id)
    {
        $contest = self::$db->select('*')->from(self::$table)->where('id', $id)->limit(1)->get()->row(0, 'Contest');
        return $contest;
    }

    public function save()
    {

        if(!self::$db->insert(self::$table, $this->data))
        {
            throw new Exception("Error saving contest");
        }
        $this->data['id'] = self::$db->insert_id();
        return TRUE;
    }

    function impressions()
    {

    }
}

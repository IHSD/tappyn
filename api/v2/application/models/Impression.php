<?php defined("BASEPATH") or exit('No direct script access allowed');

class Impression extends MY_Model
{
    private static $db;

    private static $table = 'impressions';
    
    function __construct()
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function get($id)
    {
        $contest = self::$db->select('*')->from(self::$table)->where('id', $id)->limit(1)->get()->row(0, 'Impression');
        return $contest;
    }

    static function findByUser($uid)
    {

    }

    static function find($params)
    {
        $impressions = self::$db->select('*')->from(self::$table)->where($params)->get()->result('Impression');
        return $submissions;
    }

    static function count($params)
    {
        $count = self::$db->select('COUNT(*) as count')->from(self::$table)->where($params)->get()->row()->count;
        return $count;
    }
}

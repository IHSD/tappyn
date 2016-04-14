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
        $impression = self::$db->select('*')->from(self::$table)->where('id', $id)->limit(1)->get()->row(0, 'Impression');
        return $impression;
    }

    static function findByUser($uid)
    {

    }

    static function find($params)
    {
        $impressions = self::$db->select('*')->from(self::$table)->where($params)->get()->result('Impression');
        return $impressions;
    }

    static function count($params)
    {
        $count = self::$db->select('COUNT(*) as count')->from(self::$table)->where($params)->get()->row()->count;
        return (int) $count;
    }

    public function log($params = NULL)
    {
        var_dump($params);
        self::$db->insert('impressions', $params);
    }
}

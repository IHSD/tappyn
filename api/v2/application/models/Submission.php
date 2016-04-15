<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submission extends MY_Model
{
    private static $db;

    private static $table = 'submissions';

    function __construct()
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function find($params)
    {
        $submissions = self::$db->select('*')->from(self::$table)->where($params)->get()->result();
        return $submissions;
    }

    static function count($params)
    {
        $count = self::$db->select('COUNT(*) as count')->from(self::$table)->where($params)->get()->row()->count;
        return (int) $count;
    }
}

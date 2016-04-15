<?php defined("BASEPATH") or exit('No direct script access allowed');

class VoucherUsage extends MY_Model
{
    private static $db;

    private static $table = 'voucher_uses';

    function __construct()
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function get($id)
    {
        $vote = self::$db->select('*')->from(self::$table)->where('id', $id)->limit(1)->get()->row(0, 'VoucherUsage');
        return $vote;
    }

    static function findByUser($uid)
    {

    }

    static function find($params)
    {
        $votes = self::$db->select('*')->from(self::$table)->where($params)->get();
        if($votes->num_rows() == 0)
        {
            return FALSE;
        }
        return $votes->result('VoucherUsage');

    }

    static function count($params)
    {
        $count = self::$db->select('COUNT(*) as count')->from(self::$table)->where($params)->get()->row()->count;
        return (int) $count;
    }
}

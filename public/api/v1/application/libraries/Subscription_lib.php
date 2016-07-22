<?php defined("BASEPATH") or exit('No direct script access allowed');

class Subscription_lib
{
    public $data = array(

    );

    public function __construct()
    {
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get_by_user_id($user_id = 0)
    {
        $result = array();
        if (!$user_id) {
            return $result;
        }
        $result = $this->db->select('*')->from('user_subscription')->where('user_id', $user_id)->limit(1)->get()->result_array();
        return ($result) ? $result[0] : array();
    }

    public function update_level($user_id = 0, $data = array())
    {
        if (!$data['next_level']) {
            return false;
        }

        $now_ts = date('Y-m-d H:i:s');
        $temp   = array('updated_at' => $now_ts);
        $now    = $this->get_by_user_id($user_id);
        $result = false;

        // new
        if (!$now) {
            $temp['user_id']   = $user_id;
            $temp['now_level'] = $temp['next_level'] = $data['next_level'];
            $temp['start_at']  = $now_ts;
            $temp['end_at']    = date('Y-m-d H:i:s', strtotime('+1 month'));
            $result            = $this->db->insert('user_subscription', $temp);
        }
        // end
        else if ($now['end_at'] < $now_ts) {
            $temp['user_id']   = $user_id;
            $temp['now_level'] = $temp['next_level'] = $data['next_level'];
            $temp['start_at']  = $now_ts;
            $temp['end_at']    = date('Y-m-d H:i:s', strtotime('+1 month'));
            $result            = $this->db->where('id', $now['id'])->update('user_subscription', $temp);
        }
        // upgrate
        else if ($now['now_level'] < $data['next_level']) {
            $temp['user_id']   = $user_id;
            $temp['now_level'] = $temp['next_level'] = $data['next_level'];
            $result            = $this->db->where('id', $now['id'])->update('user_subscription', $temp);
        }
        // downgrate or same
        else {
            $temp['user_id']    = $user_id;
            $temp['next_level'] = $data['next_level'];
            $result             = $this->db->where('id', $now['id'])->update('user_subscription', $temp);
        }

        return $result;
    }
}

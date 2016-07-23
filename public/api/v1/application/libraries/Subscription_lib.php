<?php defined("BASEPATH") or exit('No direct script access allowed');

class Subscription_lib
{
    public $data = array(
        10 => array('contest_limit' => 1),
        20 => array('contest_limit' => 22),
        30 => array('contest_limit' => 30),
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

        $tmp    = $this->db->select('*')->from('user_subscription')->where('user_id', $user_id)->limit(1)->get()->result_array();
        $result = ($tmp) ? $tmp[0] : array();

        $result['can_launch'] = false;
        if (isset($result['now_level']) && $result['now_level'] > 0) {
            $count = $this->db->select('COUNT(*) as count')->from('contests')
                ->where('owner', $user_id)
                ->where('paid', '1')
                ->where('start_time >=', date('Y-m-d 00:00:00'))
                ->where('start_time <=', date('Y-m-t 23:59:59'))->get()->row()->count;
            $count = (int) $count;
            $level = $this->data[$result['now_level']];
            if ($level && $level['contest_limit'] > $count) {
                $result['can_launch'] = true;
            }
            $result['count'] = $count;
            //var_dump($count);
        }
        return $result;
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

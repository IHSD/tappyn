<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest_lib
{

    public function __construct()
    {
        $this->load->model('contest');
        $this->load->library('subscription_lib');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function set_live($id)
    {
        // Check that they own the contest
        $contest = $this->contest->get($id);
        if (!$contest || ($contest->owner !== $this->ion_auth->user()->row()->id)) {
            return 'You do not own this contest brody';
        }

        if ($this->contest->get_status($contest) != 'draft') {
            return 'Aleady launched';
        }
        $sub = $this->subscription_lib->get_by_user_id($this->ion_auth->user()->row()->id);
        if ($sub['can_launch'] == false) {
            return 'You need to upgrade your current subscription to launch';
        }
        $start_time = date('Y-m-d H:i:s');
        $stop_time  = date('Y-m-d H:i:s', strtotime('+7 days'));
        $data       = array('paid' => 1, 'start_time' => $start_time, 'stop_time' => $stop_time);
        return $this->contest->update($id, $data);
    }
}

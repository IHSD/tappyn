<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest_lib
{

    public function __construct()
    {
        $this->load->model('contest');

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
        $start_time = date('Y-m-d H:i:s');
        $stop_time  = date('Y-m-d H:i:s', strtotime('+7 days'));
        $data       = array('paid' => 1, 'start_time' => $start_time, 'stop_time' => $stop_time);
        return $this->contest->update($id, $data);
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Ad_model extends MY_Model
{
    protected $errors = false;
    public function __construct()
    {
        parent::__construct();
        $this->talbe = 'ads';
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('ads', $data);
    }

    public function create($contest_id, $submission_id, $platform)
    {
        return $this->db->insert('ads', array(
            'contest_id' => $contest_id,
            'submission_id' => $submission_id,
            'platform' => $platform,
        ));
    }

    public function select_by_contest_id($contest_id)
    {
        return $this->db->select("*")->from("ads")->where('contest_id', $contest_id)->get();
    }

    public function get_ungraph()
    {
        return $this->db->select("*")->from("ads")->where('done', '0')->where('end_time <', time())->limit(5)->order_by('id', 'asc')->get()->result();
    }

    public function get_undone($contest_id)
    {
        return $this->db->select("*")->from("ads")->where('done', '0')->where('contest_id', $contest_id)->order_by('id', 'asc')->get()->result();
    }

    public function is_testing_status($contest_id)
    {
        foreach ($this->select_by_contest_id($contest_id)->result() as $ad) {
            if ($ad->done == 0) {
                return true;
            }
        }
        return false;
    }
}

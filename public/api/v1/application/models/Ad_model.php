<?php defined("BASEPATH") or exit('No direct script access allowed');

class Ad_model extends MY_Model
{
    protected $errors = false;
    public function __construct()
    {
        parent::__construct();
        $this->talbe = 'ads';
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
}

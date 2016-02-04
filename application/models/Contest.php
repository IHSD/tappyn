<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get($id)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $id)->get();
        if($contest)
        {
            return $contest->row();
        }
        return false;
    }

    public function fetch()
    {

    }

    public function submissions()
    {

    }
}

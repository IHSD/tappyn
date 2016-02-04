<?php defined("BASEPATH") or exit("No direct script access allowed");

class Submission extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getByUser($uid, $params = array())
    {
        $where = array();
        if(!empty($params))
        {
            foreach($params as $key => $value)
            {
                $where[$key] = $value;
            }
        }
        $where['owner'] = $uid;
        $submissions = $this->db->select('*')->from('submissions')->where($where)->order_by('created_at', 'desc')->get();
        if($submissions)
        {
            $results = $submissions->result();
            foreach($results as $result)
            {
                $result->contest = $this->parentContest($result->contest_id);
            }

            return $results;
        }
        return FALSE;
    }

    public function count($params)
    {
        $count = $this->db->select('COUNT(*) as count')->from('submissions')->where($params)->get();
        if($count && $count->num_rows() > 0)
        {
            return $count->row()->count;
        }
        return FALSE;
    }

    public function parentContest($cid)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $cid)->limit(1)->get();
        if($contest !== FALSE)
        {
            return $contest->row();
        }
        return FALSE;
    }
}

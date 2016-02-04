<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends CI_Model
{
    protected $errors = false;
    protected $messages = false;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get($id)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $id)->limit(1)->get();
        if($contest && $contest->num_rows() == 1)
        {
            return $contest->row();
        }
        return false;
    }

    public function fetchAll($params = null)
    {
        $contests = $this->db->select('*')->from('contests')->get();
        if($contests && $contests->num_rows() > 0)
        {
            $results = $contests->result();
            foreach($results as $result)
            {
                $result->submission_count = $this->submissionsCount($result->id);
            }
            return $results;
        }
        else if($contests && $contests->num_rows() == 0)
        {
            return array();
        }
        return false;
    }

    public function submissions()
    {

    }

    public function submissionsCount($contest_id)
    {
        $count = $this->db->select('COUNT(*) as count')->from('submissions')->where('contest_id', $contest_id)->get();
        if($count !== FALSE)
        {
            return $count->row()->count;
        }
        return false;
    }

    public function count($params = null)
    {
        $count = $this->db->select("COUNT(*) as count")->from('contests')->get();
        if($count && $count->num_rows() == 1)
        {
            return $count->row()->count;
        }
        return false;
    }

    public function create($data)
    {
        if(!$this->validate())
        {
            return false;
        }

        if($this->db->insert('contests', $data))
        {
            $this->messages = 'Contest successfully created';
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function messages()
    {
        return $this->messages;
    }

    public function validate()
    {
        return true;
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends CI_Model
{
    protected $errors = false;
    protected $messages = false;
    protected $where = array();
    protected $table = 'contests';
    protected $select = '*';
    protected $order_by = 'contests.id';
    protected $order_dir = 'desc';
    protected $limit = NULL;
    protected $offset = NULL;
    protected $group_by = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function select($sel)
    {
        if(!is_array($sel))
        {
            $this->select = $sel;
        } else {
            $this->select = implode(',', $sel);
        }
        return $this;
    }

    public function where($key, $value = FALSE)
    {
        if(is_array($key))
        {
            foreach($key as $k => $v)
            {
                $this->where($k, $v);
            }
        }
        $this->where[$k] = $v;
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function group_by($group)
    {
        $this->group_by = $group;
        return $this;
    }

    public function order_by($col, $order = 'desc')
    {
        $this->order_by = $col;
        $this->order_dir = $order;
        return $this;
    }

    public function fetch()
    {
        $this->db->select($this->select);
        $this->db->from($this->table);
        if(!empty($this->where))
        {
            foreach($this->where as $where)
            {
                $this->db->where($where);
            }
            $this->where = array();
        }

        if(!empty($this->like))
        {
            $this->db->like($this->like);
            $this->like = array();
        }

        if(!is_null($this->limit) && !is_null($this->offset))
        {
            $this->db->limit($this->limit, $this->offset);
            $this->limit = NULL;
            $this->offset = NULL;
        }
        else if(!is_null($this->limit))
        {
            $this->db->limit($this->limit);
            $this->imit = NULL;
        }

        $this->db->order_by($this->order_by, $this->order_dir);
        $this->order_by = 'id';
        $this->order_dir = 'desc';

        $this->response = $this->db->get();
        return $this;
    }

    public function row()
    {
        return $this->response->row();
    }

    public function result()
    {
        return $this->response->result();
    }

    public function log_impression($cid)
    {
        $this->db->insert('impressions', array(
            'contest_id' => $cid,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        ));
    }
    public function get($id)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $id)->limit(1)->get();
        if($contest && $contest->num_rows() == 1)
        {
            $contest = $contest->row();
            $contest->submission_count = $this->submissionsCount($contest->id);
            $contest->company = $this->db->select('*')->from('users')->join('profiles', 'users.id = profiles.id', 'left')->where('users.id', $contest->owner)->limit(1)->get()->row();
            return $contest;
        }
        return false;
    }

    public function fetchAll($params = array(), $sort_by = 'start_time', $sort_order = 'desc', $limit = 20, $offset = false)
    {
        $this->db->select('*')->from('contests');
        if(!empty($params)) $this->db->where($params);
        $this->db->order_by($sort_by, $sort_order);
        if($offset) {
            $this->db->limit($limit, $offset);
        } else {
            $this->db->limit($limit);
        }
        $contests = $this->db->get();
        if($contests && $contests->num_rows() > 0)
        {
            $results = $contests->result();
            foreach($results as $result)
            {
                $result->submission_count = $this->submissionsCount($result->id);
                $result->company = $this->db->select('*')->from('users')->join('profiles', 'users.id = profiles.id', 'left')->where('users.id', $result->owner)->limit(1)->get()->row();
            }
            return $results;
        }
        else if($contests && $contests->num_rows() == 0)
        {
            return array();
        }
        return false;
    }

    public function submissions($cid)
    {
        $submissions = $this->db->select('*')->from('submissions')->where('contest_id', $cid)->order_by('created_at', 'desc')->get();
        if(!$submissions)
        {
            return FALSE;
        }
        $submissions = $submissions->result();
        foreach($submissions as $submission)
        {
            $submission->owner = $this->db->select('first_name, last_name, email')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();
            $submission->vote_count = rand(1,5);
        }
        return $submissions;
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

    public function count($params = array())
    {
        $this->db->select("COUNT(*) as count")->from('contests');
        if(!empty($params)) $this->db->where($params);
        $count = $this->db->get();
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

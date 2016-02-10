<?php  defined("BASEPATH") or exit("No direct script access allowed");

class User extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function profile($uid)
    {
        $profile = $this->db->select('*')->from('profiles')->where('id', $uid)->limit(1)->get();
        if($profile !== FALSE)
        {
            return $profile->row();
        }
        return FALSE;
    }

    public function saveProfile($uid, $data)
    {
        $check = $this->db->select('*')->from('profiles')->where('id', $uid)->limit(1)->get();
        if($check !== FALSE)
        {
            if($check->num_rows() > 0)
            {
                // Update
                if($this->db->where('id', $uid)->update('profiles', $data))
                {
                    return TRUE;
                } else {
                    die(json_encode($this->db->error()));
                }
            }
            else
            {
                $data['id'] = $uid;
                if($this->db->insert('profiles', $data))
                {
                    return TRUE;
                } else {
                    die(json_encode($this->db->error()));
                }
            }
        }
        return FALSE;
    }

    public function fetch()
    {
        $limit = $this->input->get('per_page') ? $this->input->get('per_page') : 10;
        $offset = $this->input->get('page') ? ($this->input->get('page')) : 0;
        $users = $this->db->select('*')->from('users')->join('profiles', 'users.id = profiles.id', 'left')->order_by('created_on', 'desc')->limit($limit, $offset)->get();
        if($users)
        {
            return $users->result();
        }
        return FALSE;
    }

    public function count()
    {
        return $this->db->select("COUNT(*) as count")->from('users')->get()->row()->count;
    }
}

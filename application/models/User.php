<?php  defined("BASEPATH") or exit("No direct script access allowed");

class User extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
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
                return $this->db->where('id', $uid)->update('profiles', $data);
            }
            else
            {
                $data['id'] = $uid;
                return $this->db->insert('profiles', $data);
            }
        }
        return FALSE;
    }
}

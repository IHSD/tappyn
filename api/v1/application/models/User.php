<?php  defined("BASEPATH") or exit("No direct script access allowed");

class User extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'users';
        $this->order_by = 'users.id';
        $this->order_dir = 'desc';
        $this->load->database();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Get the profile of a user
     * @param  integer $uid
     * @return object
     */
    public function profile($uid)
    {
        $profile = $this->db->select('*')->from('profiles')->where('id', $uid)->limit(1)->get();
        if($profile !== FALSE)
        {
            return $profile->row();
        }
        return FALSE;
    }

    public function canEditAge($uid)
    {
        return is_null($this->profile($uid)->age);
    }

    public function canEditGender($uid)
    {
        return is_null($this->profile($uid)->gender);
    }

    public function canEditLocation($uid)
    {
        return is_null($this->profile($uid)->state);
    }

    /**
     * Save a user profile
     * @param  integer $uid
     * @param  array $data
     * @return boolean
     */
    public function saveProfile($uid, $data)
    {
        if($data['company_url'] && (strpos('http://', $data['company_url']) === FALSE)) $data['company_url'] = 'http://'.$data['company_url'];
        if($data['facebook_url'] && (strpos('http://', $data['facebook_url']) === FALSE)) $data['facebook_url'] = 'http://'.$data['facebook_url'];
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
                    error_log($this->db->error()['message']);
                    $this->errors = "An unexpected error occured {$this->db->error()['code']}";
                }
            }
            else
            {
                $data['id'] = $uid;
                if($this->db->insert('profiles', $data))
                {
                    return TRUE;
                } else {
                    error_log($this->db->error()['message']);
                    $this->errors = "An unexpected error occured {$this->db->error()['code']}";
                }
            }
        }
        return FALSE;
    }

    public function account($uid)
    {
        $account = $this->db->select('*')->from('stripe_accounts')->where('user_id', $uid)->limit(1)->get();
        if($account && $account->num_rows() == 1)
        {
            return $account->row()->account_id;
        }
        return false;
    }

    public function accountDetails($aid)
    {
        $account = $this->db->select('*')->from('stripe_accounts')->where('account_id', $aid)->get();
        if($account && $account->num_rows() > 0)
        {
            return $account->row();
        }
        return FALSE;
    }
    public function submissonCount($uid)
    {
        $count = $this->db->select('COUNT(*) as count')->from('submissions')->where(array('owner' => $uid))->get();
        if($count)
        {
            return $count->row()->count;
        }
        return FALSE;
    }

    public function attribute_points($id, $amount)
    {
        $points = (int)$amount;
        $current_points = (int)$this->ion_auth->user()->row()->points;
        $check = $this->db->where('id', $id)->update('users', array('points' => ($points + $current_points)));
        return $check;
    }

    public function following($id)
    {
        $res = array();
        $follows = $this->db->selectg('*')->from('follows')->where('follower', $id)->get()->result();
        foreach($follows as $follow)
        {
            $res[] = $follow->following;
        }
        return $res;
    }

    /**
     * Get count of users
     * @param array $where
     * @param array $like
     * @return integer  Count of rows given params
     */
    public function count($where = array(), $like = array())
    {
        $this->db->select("COUNT(*) as count")->from('users');
        if(!empty($where)) $this->db->where($where);
        if(!empty($like)) $this->db->like($like);

        return (int) $this->db->get()->row()->count;
    }

    public function errors()
    {
        return $this->errors;
    }
}

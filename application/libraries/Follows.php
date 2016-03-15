<?php defined("BASEPATH") or exit('No direct script access allowed');

class Follows
{
    protected $errors;

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Allow one user to follow another
     * @param  integer  $follower  ID of the person following
     * @param  integer $following  ID of the person to follow
     * @return boolean
     */
    public function follow($follower, $following)
    {
        if($this->is_following($follower, $following))
        {
            return TRUE;
        }
        if($this->db->insert('follows', array('follower' => $follower, 'following' => $following, 'created' => time())))
        {
            return TRUE;
        } else {
            $this->errors = "There was an error following that user";
            return FALSE;
        }
    }

    /**
     * Get all people I follow
     * @param  integer $id ID of the user
     * @return array
     */
    public function following($id)
    {
        $followers = $this->db->select('*')->from('follows')->where('follower', $id)->get();
        if(!$followers || $followers->num_rows() == 0)
        {
            return array();
        } else {
            return $followers->result();
        }
    }

    /**
     * Get people who follow me
     * @param  integer $id ID of the user
     * @return array
     */
    public function followers($id)
    {
        $following = $this->db->select('*')->from('follows')->where('following', $id)->get();
        if(!$following || $following->num_rows() == 0)
        {
            return array();
        } else {
            return $following->result();
        }
    }

    public function is_following($follower, $following)
    {
        $check = $this->db->select('*')->from('follows')->where(array('follower' => $follower, 'following' => $following))->get();
        if(!$check || $check->num_rows() > 0)
        {
            return TRUE;
        }
        return FALSE;
    }

    public function countFollowing($id)
    {
        return $this->count(array('follower' => $id));
    }

    public function countFollowers($id)
    {
        return $this->count(array('following' => $id));
    }

    public function count($params)
    {
        $count = $this->db->select('COUNT(*) as count')->from('follows')->where($params)->get();
        error_log($this->db->last_query());
        if(!$count || $count->num_rows() == 0)
        {
            return 0;
        }
        return (int)$count->row()->count;
    }
    public function errors()
    {
        return $this->errors;
    }
}

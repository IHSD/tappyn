<?php  defined("BASEPATH") or exit("No direct script access allowed");

class User extends CI_Model
{
    protected $where = array();
    protected $table = 'users';
    protected $select = '*';
    protected $order_by = 'users.id';
    protected $order_dir = 'desc';
    protected $limit = NULL;
    protected $offset = NULL;
    protected $group_by = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Set Where Query Parameters
     * @param  mixed $where
     * @return self
     */
    public function where($where)
    {
        if(!is_array($where))
        {
            $where = array($where => $value);
        }

        array_push($this->where, $where);

        return $this;
    }

    /**
     * Set Select Section of DB Query
     * @param  mixed $select
     * @return self
     */
    public function select($select)
    {
        if(!is_array($select))
        {
            $this->select = $select;
        }
        $this->select = implode(',',$select);
        return $this;
    }

    /**
     * Set order_by section of query
     * @param  string $col   Column to sort on
     * @param  string $order Direction to sort
     * @return self
     */
    public function order_by($col, $order = 'desc')
    {
        $this->order_by = $col;
        $this->order_dir = $order;
        return $this;
    }

    /**
     * Set limit seciton of query
     * @param  integer $limit
     * @return self
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set offset section of query
     * @param integer $offset
     * @return self
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Set like section of DB query
     * @param  string $like     Column to run like against
     * @param  mixed $value
     * @param  string $position Type of liek statement
     * @return self
     */
    public function like($like, $value = NULL, $position = 'both')
    {
        if(!is_array($like))
        {
            $like = array($like => array(
                'value' => $value,
                'position' => $position,
            ));
        }

        array_push($this->like, $like);

        return $this;
    }

    /**
     * Return first row of resultset
     * @return object
     */
    public function row()
    {
        return $this->response->row();
    }

    /**
     * Return entire resultset
     * @return array
     */
    public function result()
    {
        return $this->response->result();
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

    /**
     * Save a user profile
     * @param  integer $uid
     * @param  array $data
     * @return boolean
     */
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

    /**
     * Execute the generated db query
     * @return self
     */
    public function fetch()
    {
        $this->db->select($this->select);
        $this->db->from($this->table);
        $this->db->join('profiles', $this->table.'.id = profiles.id', 'left');
        if(!empty($this->where))
        {
            $this->db->where($where);
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

    public function account($uid)
    {
        $account = $this->db->select('*')->from('stripe_accounts')->where('user_id', $uid)->limit(1)->get();
        if($account && $account->num_rows() == 1)
        {
            return $account->row()->account_id;
        }
        return false;
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

        return $this->db->get()->row()->count;
    }
}

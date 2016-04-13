<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contest extends MY_Model
{
    protected $errors = false;
    protected $messages = false;


    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->table = 'contests';
        $this->order_by = 'contests.id';
        $this->order_dir = 'desc';
    }

    public function log_impression($cid, $uid = NULL)
    {
        $this->db->insert('impressions', array(
            'contest_id' => $cid,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'user_id'    => $uid
        ));
    }

    public function views($cid)
    {
        $views = $this->db->select("COUNT(*) as count")->from('impressions')->where('contest_id', $cid)->get();
        if($views !== FALSE)
        {
            return $views->row()->count;
        }
        return FALSE;
    }

    public function get($id)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $id)->limit(1)->get();
        if($contest && $contest->num_rows() == 1)
        {
            $contest = $contest->row();
            $contest->submission_count = $this->submissionsCount($contest->id);
            $contest->company = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->limit(1)->get()->row();
            $contest->needs_winner = $this->needsWinner($contest->id);
            return $contest;
        }
        return false;
    }

    public function fetchAll($params = array(), $sort_by = 'start_time', $sort_order = 'desc', $limit = 20, $offset = false, $interests = array())
    {
        $this->db->select('*')->from('contests');
        if(!empty($params)) $this->db->where($params);
        if(!empty($interests)) $this->db->where_in('industry', $interests);
        $this->db->order_by($sort_by, $sort_order);
        if($offset) {
            $this->db->limit($limit, $offset);
        } else {
            $this->db->limit($limit);
        }
        $contests = $this->db->get();
        error_log($this->db->last_query());
        if($contests && $contests->num_rows() > 0)
        {
            $results = $contests->result();
            foreach($results as $result)
            {
                $result->submission_count = $this->submissionsCount($result->id);
                $result->company = $this->db->select('*')->from('profiles')->where('id', $result->owner)->limit(1)->get()->row();
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
            $submission->owner = $this->db->select('first_name, last_name')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();
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

    public function hasUserSubmitted($uid, $cid)
    {
        $check = $this->db->select('*')->from('submissions')->where(array('owner' => $uid, 'contest_id' => $cid))->get();
        if($check)
        {
            return $check->num_rows() > 0;
        } else {
            return FALSE;
        }
    }

    public function mayUserSubmit($uid, $cid)
    {
        // Fetch the contest
        $contest = $this->contest->get($cid);
        // This contest has no age / gender restrictions
        $profile = $this->db->select('*')->from('profiles')->where('id', $uid)->limit(1)->get()->row();
        if($contest->gender == 0 && $contest->min_age == 18 && $contest->max_age == 65)
        {
            return TRUE;
        }

        if($this->userIsGender($contest->gender, $profile->gender) && $this->userInAgeRange($contest->min_age, $contest->max_age, $profile->age))
        {
            return TRUE;
        }
        return TRUE;
    }

    public function userIsGender($gender_req, $gender_sup)
    {
        if($gender_req == 0 || ($gender_req == $gender_sup)) return TRUE;
        return FALSE;
    }

    public function userInAgeRange($min, $max, $age)
    {
        if($age > 45) $age = 45;
        // There are no age requirementss
        if($min == 18 && $max == 45)
        {
            return TRUE;
        }
        return ($min <= $age && $age <= $max);
    }

    public function create($data)
    {
        if(!$data = $this->validate($data))
        {
            return false;
        }

        if($this->db->insert('contests', $data))
        {
            $this->messages = 'Contest successfully created';
            return $this->db->insert_id();
        }
        $this->errors = $this->db->error()['message'];
        return FALSE;
    }

    public function needsWinner($cid)
    {
        $check = $this->db->select('*')->from('payouts')->where('contest_id', $cid)->limit(1)->get();
        if($check && $check->num_rows() == 0)
        {
            return TRUE;
        }
        return FALSE;
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('contests', $data);
    }

    public function errors()
    {
        return $this->errors;
    }

    public function messages()
    {
        return $this->messages;
    }

    public function validate($data)
    {
        switch($data['age'])
        {
            case 0:
                $data['min_age'] = 18;
                $data['max_age'] = 45;
                break;
            case 1:
                $data['min_age'] = 18;
                $data['max_age'] = 24;
                break;
            case 2:
                $data['min_age'] = 25;
                $data['max_age'] = 34;
                break;
            case 3:
                $data['min_age'] = 35;
                $data['max_age'] = 45;
                break;
            case 4:
                $data['min_age'] = 45;
                $data['max_age'] = 45;
                break;
        }
        unset($data['age']);
        return $data;
    }

    public function delete($id)
    {
        $contest = $this->get($id);
        if(!$contest)
        {
            $this->errors = "That contest does not exist";
            return false;
        }
        if($contest->owner !== $this->ion_auth->user()->row()->id)
        {
            $this->errors = "You dont own that contest brody";
            return FALSE;
        }
        return $this->db->where('id', $id)->delete('contests');
    }
}

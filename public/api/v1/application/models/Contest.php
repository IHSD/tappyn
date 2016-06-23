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
        $this->load->model('ad_model');
        $this->config->load('secrets');
        $this->load->library('payout');
    }

    public function log_impression($cid, $uid = null)
    {
        $this->db->insert('impressions', array(
            'contest_id' => $cid,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'user_id' => $uid,
        ));
    }

    public function views($cid)
    {
        $views = $this->db->select("COUNT(*) as count")->from('impressions')->where('contest_id', $cid)->get();
        if ($views !== false) {
            return $views->row()->count;
        }
        return false;
    }

    public function get($id)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $id)->limit(1)->get();
        if ($contest && $contest->num_rows() == 1) {
            $contest = $contest->row();
            $contest->submission_limit = (int) $contest->submission_limit;
            $contest->submission_count = (int) $this->submissionsCount($contest->id);
            $contest->company = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->limit(1)->get()->row();
            $contest->min_age = (int) $contest->min_age;
            $contest->max_age = (int) $contest->max_age;
            unset($contest->company->stripe_customer_id);
            $contest->needs_winner = $this->needsWinner($contest->id);
            $contest->location = explode(',', $contest->location);
            $contest->industry = explode(',', $contest->industry);
            $contest->tone_of_voice_box = explode(',', $contest->tone_of_voice_box);
            return $contest;
        }
        return false;
    }

    public function fetchAll($params = array(), $sort_by = 'start_time', $sort_order = 'asc', $limit = 20, $offset = false, $interests = array())
    {
        $this->db->select('*')->from('contests');
        if (!empty($params)) {
            $this->db->where($params);
        }

        if (!empty($interests)) {
            $this->db->where_in('industry', $interests);
        }

        $this->db->order_by($sort_by, $sort_order);
        if ($offset) {
            $this->db->limit($limit, $offset);
        } else {
            $this->db->limit($limit);
        }
        $contests = $this->db->get();
        if ($contests && $contests->num_rows() > 0) {
            $results = $contests->result();
            foreach ($results as $result) {
                $result->submission_count = $this->submissionsCount($result->id);
                $result->company = $this->db->select('*')->from('profiles')->where('id', $result->owner)->limit(1)->get()->row();
                $result->industry = explode(',', $result->industry);
                unset($result->company->stripe_customer_id);
            }
            return $results;
        } else if ($contests && $contests->num_rows() == 0) {
            return array();
        }
        return false;
    }

    public function submissions($cid)
    {
        $submissions = $this->db->select('*')->from('submissions')->where('contest_id', $cid)->order_by('created_at', 'asc')->get();
        if (!$submissions) {
            return false;
        }
        $submissions = $submissions->result();
        foreach ($submissions as $submission) {
            $submission->owner = $this->db->select('first_name, last_name')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();
        }
        return $submissions;
    }

    public function submissionsCount($contest_id)
    {
        $count = $this->db->select('COUNT(*) as count')->from('submissions')->where('contest_id', $contest_id)->get();
        if ($count !== false) {
            return $count->row()->count;
        }
        return false;
    }

    public function hasUserSubmitted($uid, $cid)
    {
        $check = $this->db->select('*')->from('submissions')->where(array('owner' => $uid, 'contest_id' => $cid))->get();
        if ($check) {
            return $check->num_rows() > 0;
        } else {
            return false;
        }
    }

    public function mayUserSubmit($uid, $cid)
    {
        // Fetch the contest
        $contest = $this->contest->get($cid);
        // This contest has no age / gender restrictions
        $profile = $this->db->select('*')->from('profiles')->where('id', $uid)->limit(1)->get()->row();
        if ($contest->gender == 0 && $contest->min_age == 18 && $contest->max_age == 65) {
            return true;
        }

        if ($this->userIsGender($contest->gender, $profile->gender) && $this->userInAgeRange($contest->min_age, $contest->max_age, $profile->age)) {
            return true;
        }
        return true;
    }

    public function userIsGender($gender_req, $gender_sup)
    {
        if ($gender_req == 0 || ($gender_req == $gender_sup)) {
            return true;
        }

        return false;
    }

    public function userInAgeRange($min, $max, $age)
    {
        if ($age > 45) {
            $age = 45;
        }

        // There are no age requirementss
        if ($min == 18 && $max == 45) {
            return true;
        }
        return ($min <= $age && $age <= $max);
    }

    public function create($data)
    {
        if (!$data) {
            return false;
        }

        if ($this->db->insert('contests', $data)) {
            $this->messages = 'Contest successfully created';
            return $this->db->insert_id();
        }
        $this->errors = "There was an error creating your contest";
        error_log("Failed creating contest::" . $this->db->error()['message']);
        return false;
    }

    public function needsWinner($cid)
    {
        $check = $this->db->select('*')->from('payouts')->where('contest_id', $cid)->limit(1)->get();
        if ($check && $check->num_rows() == 0) {
            return true;
        }
        return false;
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
        switch ($data['age']) {
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
        if (!$contest) {
            $this->errors = "That contest does not exist";
            return false;
        }
        if ($contest->owner !== $this->ion_auth->user()->row()->id) {
            $this->errors = "You dont own that contest brody";
            return false;
        }
        return $this->db->where('id', $id)->delete('contests');
    }

    public function get_status($contest)
    {
        $status = 'live';
        if ($contest->paid == 0) {
            $status = 'draft';
        } else if ($this->payout->exists(array('contest_id' => $contest->id))) {
            $status = 'purchased';
        } else if ($contest->submission_count >= $contest->submission_limit) {
            $status = 'pending_purchase';
        } else if ($contest->stop_time < date('Y-m-d H:i:s')) {
            if ($this->ad_model->is_testing_status($contest->id)) {
                $status = 'testing';
            } else {
                $status = 'pending_purchase';
            }
        } else if ($contest->start_time > date('Y-m-d H:i:s')) {
            $status = 'scheduled';
        }
        return $status;
    }
}

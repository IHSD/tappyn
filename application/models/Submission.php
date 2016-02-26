<?php defined("BASEPATH") or exit("No direct script access allowed");

class Submission extends MY_Model
{
    protected $table = 'submissions';

    protected $metafields = array(
        'submissions.id',
        'submissions.created_at',
        'submissions.updated_at',
        'submissions.owner',
        'submissions.attachment',
        'submissions.headline',
        'submissions.description',
        'submissions.text',
        'submissions.link_explanation',
        'submissions.trending',
        'submissions.contest_id'
    );
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get($sid)
    {
        $submission = $this->db->select('*')->from('submissions')->where('id', $sid)->limit(1)->get();
        if($submission && $submission->num_rows() == 1)
        {
            return $submission->row();
        }
        return FALSE;
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
                $result->payout = $this->db->select('*')->from('payouts')->where('submission_id', $result->id)->get()->row();
                $result->company = $this->db->select('*')->from('users')->join('profiles', 'users.id = profiles.id', 'left')->where('users.id', $result->contest->owner)->get()->row();

            }

            return $results;
        }
        return FALSE;
    }

    public function getActive($uid, $params = array())
    {
        $this->db->select('*')->from('submissions');
        $this->db->join('contests', 'submissions.contest_id = contests.id');
        $this->db->where(array('submissions.owner' => $uid,'contests.stop_time >' => date('Y-m-d H:i:s')));
        $this->db->order_by('submissions.created_at', 'desc');
        $submissions = $this->db->get();
        if($submissions)
        {
            if($submissions->num_rows() > 0)
            {
                $submissions = $submissions->result();
                foreach($submissions as $submission)
                {
                    $submission->contest = $this->parentContest($submission->contest_id);
                    $submission->company = $this->db->select('*')->from('users')->join('profiles', 'users.id = profiles.id', 'left')->where('users.id', $submission->contest->owner)->get()->row();
                    $submission->payout = $this->db->select('*')->from('payouts')->where('submission_id', $submission->id)->get()->row();
                }

                return $submissions;
            } else {
                return array();
            }
        }
        return FALSE;
    }

    public function getWinning($id)
    {
        $results = array();
        $payouts = $this->db->select('*')->from('payouts')->where('user_id', $id)->get();
        if(!$payouts || $payouts->num_rows() == 0) return $results;
        foreach($payouts->result() as $payout)
        {
            $submission = $this->submission->get($payout->submission_id);
            $submission->contest = $this->parentContest($submission->contest_id);
            $submission->company = $this->db->select('*')->from('users')->join('profiles', 'users.id = profiles.id', 'left')->where('users.id', $submission->contest->owner)->get()->row();
            $submission->payout = $this->db->select('*')->from('payouts')->where('submission_id', $submission->id)->get()->row();
            $results[] = $submission;
        }
        return $results;
    }

    /**
     * Return the result of data, and fetch extra mysqli_get_metadata
     * @return array
     */
    public function result($extra = true)
    {
        $results = $this->response->result();
        if($extra)
        {
            foreach($results as $result)
            {
                $result->contest = $this->parentContest($result->contest_id);
                $payout = $this->db->select('*')->from('payouts')->where('submission_id', $result->id)->get();
                if($payout)
                {
                    $result->payout = $payout->row();
                }
            }
        }
        return $results;
    }

    public function count($params)
    {
        $count = $this->db->select('COUNT(*) as count')->from('submissions')->where($params)->get();
        if($count && $count->num_rows() > 0)
        {
            return (int) $count->row()->count;
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

    public function create($data)
    {
        return $this->db->insert('submissions', $data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('submissions', $data);
    }
    public function payout($sid)
    {
        $payout = $this->db->select('*')->from('payouts')->where('submission_id', $sid)->get();
        if($payout && $payout->num_rows() > 0)
        {
            return $payout->row();
        }
        return FALSE;
    }
}

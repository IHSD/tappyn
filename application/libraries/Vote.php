<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vote {

    protected $errors = FALSE;

    public function __construct()
    {
        $this->load->model('vote_model');
        $this->config->load('upvote');
        $this->load->model('submission');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __call($method, $arguments)
    {
        if(!method_exists($this->vote_model, $method))
        {
            throw new Exception("Call to undefined method Vote::{$method}()");
        }
        return call_user_func_array(array($this->vote_model, $method), $arguments);
    }

    /**
     * Allow a user to upvote a submission
     * @param  integer $sid     ID of the submission to upvote
     * @param  integer $cid     ID of the contest
     * @param  integer $uid     ID of the user upvoting
     * @return boolean
     */
    public function upvote($sid, $cid, $uid)
    {
        // Get permissions on the contest level
        // Skip if theres no limit
        if($this->config->item('upvotes_per_contest') !== FALSE)
        {
            $count = $this->userVotesPerContest($uid, $cid);
            if($count > $this->config->item('upvotes_per_contest')) {
                $this->errors = "You have already voted the max of {$this->config->item('upvotes_per_contest')} times in this contest";
                return FALSE;
            }
        }

        // Get permissions on the submission level
        // Make sure the user has not voted on this submission yet,
        // and they also are not the creator
        $check = $this->db->select("*")->from('submissions')->where(array('id' => $sid, 'owner' => $uid))->limit(1)->get();
        if($check->num_rows() == 1)
        {
            $this->errors = "You can't vote for submissions you've created!";
            return false;
        }

        $check = $this->db->select('*')->from('votes')->where(array('user_id' => $uid, 'submission_id' => $sid))->limit(1)->get();
        if($check->num_rows() == 1)
        {
            $this->errors = "You have already voted for this submission";
            return FALSE;
        }

        // The user may vote, so we create it
        if($this->vote_model->create($uid, $sid, $cid))
        {
            return TRUE;
        }
        return FALSE;
    }

    public function mayUserVote($sid, $uid)
    {
        $check = $this->db->select('*')->from('votes')->where(array('user_id' => $uid, 'submission_id' => $sid))->limit(1)->get();
        if(!$check || $check->num_rows() == 1)
        {
            return FALSE;
        }
        return TRUE;
    }

    public function userVotesPerContest($uid, $cid)
    {
        $this->db->select('COUNT(*) as count')->from('votes')->where(array('contest_id' => $cid, 'user_id' => $uid));
        if($count = $this->db->get())
        {
            return $count->row()->count;
        }
        //If there is an error, let's guranatee the user cant vote, but not throw an exception
        return 10000;
    }

    public function dole_out_points($sid)
    {
        $users = $this->db->select('*')->from('votes')->where('submission_id', $sid)->get();
        if(!$users)
        {
            error_log("Exception at ".__FILE__."::".__LINE__."");
            return;
        }
        foreach($users->result() as $user)
        {
            $this->user->attribute_points($user->id, $this->config->item('points_per_upvote_winning'));
        }
        return;
    }

    public function errors()
    {
        return $this->errors;
    }
}

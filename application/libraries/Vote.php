<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vote {
    public function __construct()
    {
        $this->load->model('vote_model');
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
     * @param  integer $uid     ID of the user upvoting
     * @return boolean
     */
    public function upvote($sid, $uid)
    {
        // Check that the user has not already voted for this contest
        // Check the user has not already vote for this submission
        // Create the vote!
    }
}

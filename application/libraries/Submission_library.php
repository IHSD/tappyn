<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submission_library
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Create a contest submission
     * @param  array $data Fields required for creation
     * @return boolean
     */
    public function create($data)
    {
        if(!$this->userCanSubmit($data['uid'], $data['contest_id']))
        {
            $this->error = 'You have already put in a submission for that contest';
            return false;
        }
        return $this->submission->create($data);
    }

    /**
     * Ensure that the user has not already submitted to the contest
     * @param  integer $uid
     * @param  integer $contest_id
     * @return boolean
     */
    public function userCanSubmit($uid, $contest_id)
    {
        $check = $this->db->select('*')->from('submissions')->where(array('contest_id' => $contest_id, 'owner' => $uid))->limit(1)->get();
        if($check && $check->num_rows() > 0)
        {
            return FALSE;
        }
        return TRUE;
    }
}

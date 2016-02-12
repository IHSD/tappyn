<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payouts
{
    protected $errors = FALSE;

    public function __construct()
    {
        parent::__construct();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Create a payout for a contest
     * @param  integer $cid Contest to payout for
     * @param  integer $sid Submission chosen
     * @return boolean
     */
    public function create($cid, $sid)
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $cid)->limit(1)->row();
        $submission = $this->db->select('*')->from('submissions')->where('id', $sid)->limit(1)->row();
        $insert_data = array(
            'created_at' => time(),
            'contest_id' => $cid,
            'submission_id' => $sid,
            'claimed' => 0,
            'pending' => 1,
            'user_id' => $submission->owner
        );
        return $this->db->insert('payouts', $insert_data);
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('payouts', $data);
    }

    public function errors()
    {
        return $this->errors;
    }
}

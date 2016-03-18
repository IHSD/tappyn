<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vote_model extends MY_Model
{
    protected $table = 'votes';
    protected $order_dir;
    protected $order_by;
    public function __construct()
    {
        parent::__construct();
        $this->order_by = 'votes.id';
        $this->order_dir = 'desc';
    }

    public function create($uid, $sid, $cid)
    {
        return $this->db->insert($this->table, array(
            'user_id' => (int)$uid,
            'submission_id' => (int)$sid,
            'contest_id' => (int)$cid,
            'created_at' => time()
        ));
    }
}

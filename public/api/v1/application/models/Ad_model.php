<?php defined("BASEPATH") or exit('No direct script access allowed');

class Ad_model extends MY_Model
{
    public $table = 'ads';
    public $order_by;
    public $order_dir;
    protected $errors = false;
    public function __construct()
    {
        parent::__construct();
    }

    public function create($contest_id, $submission_id, $platform)
    {
        return $this->db->insert('ads', array(
            'contest_id' => $contest_id,
            'submission_id' => $submission_id,
            'platform' => $platform,
        ));
    }
}

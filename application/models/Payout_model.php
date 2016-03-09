<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payout_model extends MY_Model
{
    protected $table = 'payouts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function update($pid, $data)
    {
        return $this->db->where('id', $pid)->update('payouts', $data);
    }
}

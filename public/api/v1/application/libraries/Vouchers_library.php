<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vouchers_library
{
    public function __construct()
    {
        $this->load->model('voucher');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->voucher, $method)) {
            throw new Exception('Undefined method Vouchers::' . $method . '() called');
        }
        return call_user_func_array(array($this->voucher, $method), $arguments);
    }

    public function uses($vid)
    {
        $uses = $this->db->select('*')->from('voucher_uses')->where('voucher_id', $vid)->get();
        if (!$uses || $uses->num_rows() == 0) {
            return array();
        }
        $uses = $uses->result();
        foreach ($uses as $use) {
            $use->contest = $this->contest->get($use->contest_id);
            $company = $this->ion_auth->user($use->user_id)->row();
            $company->profile = $this->user->profile($use->user_id);
            $use->company = $company;
        }
        return $uses;
    }
    /**
     * Create a new voucher
     * @param  array $data Voucher Data
     * @return integer|boolean
     */
    public function create($data)
    {
        // Create the voucher
        $id = $this->voucher->create($data);

        if (!$id) {
            return false;
        }

        return $id;

        // Do post processing for voucher creation
    }

    /**
     * Log the redeemed voucher
     * @param  integer $vid ID of the voucher
     * @param  integer $cid ID of the contest
     * @return boolean
     */
    public function redeem($vid, $cid, $uid = 0)
    {
        // Check that the voucher is still valid
        if (!$this->is_valid($vid)) {
            // Voucher is invalid
            return false;
        }

        if ($this->voucher->redeem($vid, $cid, $uid)) {
            return true;
        }
        return false;
    }
}

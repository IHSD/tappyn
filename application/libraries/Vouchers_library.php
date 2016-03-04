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
        if (!method_exists( $this->voucher, $method) )
        {
            throw new Exception('Undefined method Vouchers::' . $method . '() called');
        }
        return call_user_func_array( array($this->voucher, $method), $arguments);
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

        if(!$id)
        {
            return FALSE;
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
    public function redeem($vid, $cid)
    {
        // Check that the voucher is still valid
        if(!$this->is_valid($vid))
        {
            // Voucher is invalid
            return FALSE;
        }

        if($this->voucher->redeem($vid, $cid))
        {
            return TRUE;
        }
        return FALSE;
    }
}

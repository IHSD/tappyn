<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vouchers
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('voucher');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __call($method, $arguments)
    {
        if (!method_exists( $this->ion_auth_model, $method) )
        {
            throw new Exception('Undefined method Vouchers::' . $method . '() called');
        }
        return call_user_func_array( array($this->ion_auth_model, $method), $arguments);
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

        // Do post processing for voucher creation
    }

    public function redeem($vid)
    {
        // Check that the voucher is still valid
        if(!$this->is_valid($vid))
        {
            // Voucher is invalid
            return FALSE;
        }

    }
}

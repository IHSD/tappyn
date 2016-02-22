<?php defined("BASEPATH") or exit('No direct script access is allowed');

class Stripe_account extends CI_Model
{
    protected $errors;
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function errors()
    {
        return $this->errors;
    }
}

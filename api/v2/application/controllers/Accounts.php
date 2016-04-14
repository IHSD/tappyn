<?php defined("BASEPATH") or exit('No direct script access allowed');

class Accounts extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('stripe/account');
    }

    public function details()
    {
        var_dump($this->account);
    }
}

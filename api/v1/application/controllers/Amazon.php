<?php defined("BASEPATH") or exit('No direct script access allowed');

class Amazon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('s3');
    }

    public function test()
    {
        $this->s3->test();
    }

    public function connect()
    {
        $this->responder->data(array('access_token' => $this->s3->connect()))->respond();
    }
}

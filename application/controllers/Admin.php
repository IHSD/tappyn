<?php defined("BASEPATH") or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    protected $ips = array(
        '127.0.0.1',
        '::1',
    )
    public function __construct()
    {
        parent::__construct();
        if(!is_cli()) { die('You may not access this area'); }
        if(!in_array($_SERVER['REMOTE_ADDR'], $this->ips)) { die('You may not access this area'); }
    }
}

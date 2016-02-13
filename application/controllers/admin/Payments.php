<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            $this->session->set_flashdata('error', 'You dont have permission to access this area');
            redirect('contests/index', 'refresh');
        }
    }
    public function test()
    {
        $this->load->view('admin/test');
    }
}

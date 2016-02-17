<?php defined("BASEPATH") or exit('No direct script access allowed');

class Accounts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(1))
        {
            $this->session->set_flashdata('error', "You do not have permission to access that area");
            redirect('auth/login', 'refresh');
        }
        $this->load->view('templates/admin_navbar');
        $this->load->view('templates/navbar');
    }

    public function index()
    {

    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function suspend()
    {

    }

    public function respend()
    {

    }
}

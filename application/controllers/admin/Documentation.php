<?php defined("BASEPATH") or exit('No direct script access allowed');

class Documentation extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('/', 'refresh');
        }
        $this->load->view('admin/docs/navbar');
    }

    public function index()
    {
        $this->load->view('admin/docs/users');
    }


    public function users()
    {
        $this->load->view('admin/docs/users');
    }

    public function contests()
    {
        $this->load->view('admin/docs/contests');
    }

    public function submissions()
    {
        $this->load->view('admin/docs/submissions');
    }

    public function accounts()
    {
        $this->load->view('admin/docs/accounts');
    }

    public function vouchers()
    {
        $this->load->view('admin/docs/vouchers');
    }
}

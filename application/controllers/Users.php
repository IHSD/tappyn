<?php defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->session->set_flashdata('error', 'You must be logged in to access this area');
            redirect('contests/index', 'refresh');
        }
        $this->load->view('templates/navbar');
        $this->load->model('user');
    }

    public function index()
    {

    }

    public function profile()
    {
        $profile = $this->user->profile($this->ion_auth->user()->row()->id);
        if($profile !== FALSE)
        {
            $this->data['profile'] = $profile;
        } else {
            $this->data['profile'] = 'testing';
        }
        $this->load->view('users/profile', $this->data);
    }

    public function update()
    {

    }

    public function info()
    {

    }
}

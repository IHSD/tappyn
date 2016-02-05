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
        $this->load->model('submission');
        $this->load->model('contest');
    }

    public function index()
    {

    }

    /**
     * Generate a users dashboard.
     *
     * If its a company, we pull in all the contests.
     * Other wise we pull in a users submissions
     * @return void
     */
    public function dashboard()
    {
        $this->data['status'] = 'all';
        if($this->ion_auth->in_group(2))
        {
            // generate the user dashboard of submissions
            $config['base_url'] = base_url().'users/dashboard';
            $config['total_rows'] = $this->submission->count(array('owner' => $this->ion_auth->user()->row()->id));
            $config['per_page'] = 10;
            $this->pagination->initialize($config);

            $submissions = $this->submission->getByUser($this->ion_auth->user()->row()->id , array());
            if($submissions !== FALSE)
            {
                $this->data['submissions'] = $submissions;
                $this->data['pagination_links'] = $this->pagination->create_links();
            }
        } else {

        }
        $this->load->view('users/dashboard', $this->data);
    }

    public function in_progress()
    {
        $this->data['status'] = 'active';
        if($this->ion_auth->in_group(2))
        {
            // generate the user dashboard of submissions
            $config['base_url'] = base_url().'users/dashboard';
            $config['total_rows'] = $this->submission->count(array('owner' => $this->ion_auth->user()->row()->id));
            $config['per_page'] = 10;
            $this->pagination->initialize($config);

            $submissions = $this->submission->getActive($this->ion_auth->user()->row()->id , array());
            if($submissions !== FALSE)
            {
                $this->data['submissions'] = $submissions;
                $this->data['pagination_links'] = $this->pagination->create_links();
            }
        } else {

        }
        $this->load->view('users/dashboard', $this->data);
    }

    public function completed()
    {

    }

    /**
     * View a users profile
     * @return void
     */
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

    public function submissions()
    {

    }
}

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
        }
        else
        {
            $config['base_url'] = base_url().'users/dashboard';
            $config['total_rows'] = $this->contest->count(array('owner' => $this->ion_auth->user()->row()->id));
            $config['per_page'] = 10;
            $this->pagination->initialize($config);

            $contests = $this->contest->fetchAll(array('owner' => $this->ion_auth->user()->row()->id));
            if($contests !== FALSE)
            {
                $this->data['contests'] = $contests;
                $this->data['pagination_links'] = $this->pagination->create_links();
            }
        }
        $this->load->view('users/dashboard', $this->data);
    }

    public function in_progress()
    {
        $this->data['status'] = 'active';
        if($this->ion_auth->in_group(2))
        {
            // generate the user dashboard of submissions
            $config['base_url'] = base_url().'users/in_progress';
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
            $config['base_url'] = base_url().'users/dashboard';
            $config['total_rows'] = $this->contest->count(array('owner' => $this->ion_auth->user()->row()->id, 'stop_time >' => date('Y-m-d H:i:s')));
            $config['per_page'] = 10;
            $this->pagination->initialize($config);

            $contests = $this->contest->fetchAll(array('owner' => $this->ion_auth->user()->row()->id, 'stop_time >' => date('Y-m-d H:i:s')));
            if($contests !== FALSE)
            {
                $this->data['contests'] = $contests;
                $this->data['pagination_links'] = $this->pagination->create_links();
            }
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
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($this->ion_auth->in_group(2))
            {
                $data = array(
                    'age' => $this->input->post('age_range'),
                    'gender' => $this->input->post('gender'),
                    'state' => $this->input->post('state'),
                    'school' => $this->input->post('school')
                );

                if(!$this->user->saveProfile($this->ion_auth->user()->row()->id, $data))
                {
                    $this->session->set_flashdata('error', 'There was an error saving your profile');
                } else {
                    $this->session->set_flashdata('message', 'Profile successfully updated');
                }
            }
            else if($this->ion_auth->in_group(3))
            {
                // Process an image if uploaded
                $data = array(
                    'mission' => $this->input->post('mission'),
                    'extra_info' => $this->input->post('extra_info'),
                    'company_email' => $this->input->post('company_email'),
                    'company_url' => $this->input->post('company_url'),
                    'facebook_url' => $this->input->post('facebook_url')
                );

                if($this->user->saveProfile($this->ion_auth->user()->row()->id, $data))
                {
                    $this->session->set_flashdata('error', 'There was an error saving your profile');
                } else {
                    $this->session->set_flashdata('message', 'Profile successfully updated');
                }
            }
        }
        $profile = $this->user->profile($this->ion_auth->user()->row()->id);
        $this->data['profile'] = $profile;
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

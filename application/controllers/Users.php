<?php defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->session->set_flashdata('error', 'You must be logged in to access this area');
            redirect('login', 'refresh');
        }
        $this->load->view('templates/navbar');
        $this->load->model('user');
        $this->load->model('submission');
        $this->load->model('contest');
        $this->load->library('stripe/stripe_account_library');
        $this->stripe_account_id = $this->user->account($this->ion_auth->user()->row()->id);
    }

    public function index()
    {
        redirect('contests/index', 'refresh');
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
            $submissions = $this->submission->getByUser($this->ion_auth->user()->row()->id , array());
            if($submissions !== FALSE)
            {
                $this->data['submissions'] = $submissions;
            }
        }
        else
        {
            $contests = $this->contest->fetchAll(array('owner' => $this->ion_auth->user()->row()->id));
            if($contests !== FALSE)
            {
                $this->data['contests'] = $contests;
            }
        }
        $this->load->view('users/dashboard', $this->data);
    }

    public function in_progress()
    {
        $this->data['status'] = 'active';
        if($this->ion_auth->in_group(2))
        {
            $submissions = $this->submission->getActive($this->ion_auth->user()->row()->id , array());
            if($submissions !== FALSE)
            {
                $this->data['submissions'] = $submissions;
            }
        } else {
            $contests = $this->contest->fetchAll(array('owner' => $this->ion_auth->user()->row()->id, 'stop_time >' => date('Y-m-d H:i:s')));
            if($contests !== FALSE)
            {
                $this->data['contests'] = $contests;
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
     * @todo Remove previous image on update of company_logo
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
                $valid = true;
                $data = array(
                    'mission' => $this->input->post('mission'),
                    'extra_info' => $this->input->post('extra_info'),
                    'company_email' => $this->input->post('company_email'),
                    'company_url' => $this->input->post('company_url'),
                    'facebook_url' => $this->input->post('facebook_url'),
                    'name' => $this->input->post('name'),
                );
                if(isset($_FILES['logo_url']))
                {
                    $config['upload_path'] = APPPATH.'uploads/';
                    $config['allowed_types'] = 'git|jpg|jpeg|png';
                    $config['remove_spaces'] = true;
                    $config['encrypt_name'] = true;
                    $this->load->library('upload', $config);
                    if(!$this->upload->do_upload('logo_url'))
                    {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        $valid = false;
                    } else {
                        $data['logo_url'] = $this->upload->data()['file_name'];
                    }
                }
                if($valid)
                {
                    if(!$this->user->saveProfile($this->ion_auth->user()->row()->id, $data))
                    {
                        $this->session->set_flashdata('error', 'There was an error saving your profile');
                    } else {
                        $this->session->set_flashdata('message', 'Profile successfully updated');
                    }
                }
            }
        }
        $profile = $this->user->profile($this->ion_auth->user()->row()->id);
        $this->data['profile'] = $profile;
        $this->load->view('users/profile', $this->data);
    }

    public function account()
    {
        $this->data['account'] = NULL;

        // check if they have submitted any required information
        if($this->input->post('submit'))
        {
            $data = array();
            foreach($this->input->post() as $key => $value)
            {
                switch($key)
                {
                    case 'first_name':
                        $data['legal_entity.first_name'] = $value;
                        break;
                    case 'last_name':
                        $data['legal_entity.last_name'] = $value;
                        break;
                    case 'dob_day':
                        $data['legal_entity.dob.day'] = $value;
                        break;
                    case 'dob_month':
                        $data['legal_entity.dob.month'] = $value;
                        break;
                    case 'dob_year':
                        $data['legal_entity.dob.year'] = $value;
                        break;
                    case 'tos_acceptance':
                        $data['tos_acceptance.ip'] = $_SERVER['REMOTE_ADDR'];
                        $data['tos_acceptance.date'] =
                    case 'country':
                        $data['country'] = $value;

                }
            }
            if($this->stripe_account_library->update($this->stripe_account_id, $data))
            {
                $this->session->set_flashdata('message', "Account information successfully updated");
            } else {
                $this->session->set_flashdata('error', $this->stripe_account_library->errors());
            }
        }

        if($this->stripe_account_id)
        {
            if($account = $this->stripe_account_library->get($this->stripe_account_id))
            {
                $this->data['account'] = $account;
            } else {
                $this->data['error'] = $this->stripe_account_library->errors();
            }
        }

        $this->data['fields'] = array();
        foreach($this->data['account']->verification->fields_needed as $field)
        {
            $this->data['fields'][$field] = array(
                'name' => $field,
                'value' => ($this->input->post($field) ? $this->input->post($field) : ''),
                'placeholder' => $field
            );
        }
        $this->load->view('users/accounts', $this->data);
    }
}

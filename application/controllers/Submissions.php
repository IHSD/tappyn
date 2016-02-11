<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/navbar');
        $this->load->model('submission');
        $this->load->model('contest');
        $this->load->library('ion_auth');
        $this->load->library('submission_library');
        $this->load->model('ion_auth_model');
        $this->load->model('user');
        $this->load->library('mailer');
    }

    /**
     * Get all submissions for a contest
     * @param  int $contest_id
     * @return void
     */
    public function index($contest_id)
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->session->set_flashdata('error', 'You must be logged in to view submissions');
            redirect('auth/login', 'refresh');
        }
        $submissions = $this->contest->submissions($contest_id);
        $this->load->view('submissions/index', array('submissions' => $submissions));
    }

    public function show($sid)
    {
        $submission = $this->submission->get($sid);
        $submission->contest = $this->contest->get($submission->contest_id);
        $this->data['submission'] = $submission;
        $this->load->view('submissions/show', $this->data);
    }

    /**
     * Create a new submission
     *
     * @todo The long switch statement is butt fugly. Lets go back and reimplement once working
     * @return void
     */
    public function create($contest_id)
    {
        $this->data['genders'] = array(
            'GENDER' => 'Gender',
            0 => 'All',
            1 => "Male",
            2 => "Female"
        );
        $this->data['ages'] = array(
            'AGES' => 'Age',
            0 => '18-24',
            1 => '25-34',
            2 => '35-44',
            3 => '45+'
        );

        $logged_in = $this->ion_auth->logged_in();
        // Verify user is logged in
        if(!$logged_in)
        {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('name', $this->lang->line('create_user_validation_fname_label'), 'required');
            $this->form_validation->set_rules('age', "Age Range", 'required');

            // Attempt the registration
            if($this->form_validation->run() == true)
            {
                $email    = strtolower($this->input->post('email'));
                $identity = $email;
                $password = bin2hex(openssl_random_pseudo_bytes(5));
                // Parse the name for saving the user
                if(strpos($this->input->post('name'), ' ') !== FALSE)
                {
                        $parts = explode(' ',$this->input->post('name'));
                        $first_name = $parts[0];
                        unset($parts[0]);
                        $last_name = implode(' ',$parts);
                }
                else
                {
                    $first_name = $this->input->post('name');
                    $last_name = '';
                }
            }
            if($this->form_validation->run() == true &&
               $this->ion_auth_model->register($identity, $password, $email, array('first_name' => $first_name, 'last_name' => $last_name), array(2)) &&
               $this->ion_auth_model->login($identity, $password, 1))
            {
                // $this->notifyUserWithPassword($email, $password);
                $this->mailer
                    ->to($email)
                    ->from("Registration@tappyn.com")
                    ->subject('Account Successfully Created')
                    ->html($this->load->view('auth/email/inline_registration', array('email' => $email, 'password' => $password), TRUE))
                    ->send();
                    
                $this->user->saveProfile($this->ion_auth->user()->row()->id, array('age' => $this->input->post('age')));
                $logged_in = true;
            }
            else
            {
                $this->session->set_flashdata('error', (validation_errors() ? validation_errors() : ($this->ion_auth_model->errors() ? $this->ion_auth_model->errors() : 'An unknown error occured')));
            }
        }

        $this->form_validation->clear();
        if($this->ion_auth->in_group(3))
        {
            $this->session->set_flashdata('error', 'Only creators are allowed to submit to contests');
            redirect("contests/show/{$contest_id}", 'refresh');
        }

        // Get the contest, and then dynamically change form validation rules, based on the type of the contest
        $contest = $this->contest->get($contest_id);
        if(!$contest)
        {
            $this->session->set_flashdata('error', 'We couldnt find the account you were looking for');
            redirect("contests/show/{$contest_id}", 'refresh');
        }

        if($contest->submission_count >= 50)
        {
            $this->session->set_flashdata('error', "We're sorry, but this contest has reached its submission limit");
            redirect("contests/show/{$contest_id}");
        }

        if($contest->stop_time < date('Y-m-d H:i:s'))
        {
            $this->session->set_flashdata('error', "This contest has ended");
            redirect("contests/show/{$contest_id}");
        }

        if($logged_in)
        {
            if(!$this->submission_library->userCanSubmit($this->ion_auth->user()->row()->id, $contest->id))
            {
                $this->session->set_flashdata('error', 'You have already submitted to this contest');
                redirect("contests/show/{$contest_id}");
            }
            $data = array(
                'owner' => $this->ion_auth->user()->row()->id,
                'contest_id' => $contest->id
            );
        }
        // Set our static data points for the view / creation
        $this->data['contest'] = $contest;
        $email_data = array(
            'headline' => $this->input->post('headline'),
            'text' => $this->input->post('text'),
            'email' => ($this->ion_auth->user() ? $this->ion_auth->user()->row()->email : false),
            'contest' => $contest->name,
            'company' => $contest->company->name
        );
        // Generate / validate fields based on the platform type
        switch($contest->platform)
        {
            case 'facebook':
                $this->form_validation->set_rules('headline', 'Headline', 'required');
                $this->form_validation->set_rules('text', 'Text', 'required');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                    $data['headline'] = $this->input->post('headline');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data)) && $logged_in)
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Headline'] = array(
                        'name' => 'headline',
                        'id' => 'headline',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('headline')
                    );
                    $fields['Text'] = array(
                        'name' => 'text',
                        'id' => 'text',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('text')
                    );
                    $fields['Link Explanation'] = array(
                        'name' => 'link_explanation',
                        'id' => 'link_explanation',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('link_explanation')
                    );

                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
                }
            break;
            case 'google':
                $this->form_validation->set_rules('headline', 'Headline', 'required');
                $this->form_validation->set_rules('text', "text", 'required');
                if($this->form_validation->run() == true)
                {
                    $data['headline'] = $this->input->post('headline');
                    $data['text'] = $this->input->post('text');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data)) && $logged_in)
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Headline'] = array(
                        'name' => 'headline',
                        'id' => 'headline',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('headline')
                    );
                    $fields['Description'] = array(
                        'name' => 'description',
                        'id' => 'description',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('description')
                    );
                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
                }
            break;
            case 'twitter':
                $this->form_validation->set_rules('text', 'Text', 'required');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data)) && $logged_in)
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Text'] = array(
                        'name' => 'text',
                        'id' => 'text',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('text')
                    );
                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
                }
            break;
            case 'trending':

            break;
            case 'tagline':

            break;
            case 'general':
                $this->form_validation->set_rules('text', 'Text', 'required');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data)) && $logged_in)
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Text'] = array(
                        'name' => 'text',
                        'id' => 'text',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('text')
                    );
                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
            }
            break;
        }
        $this->session->set_flashdata('error', validation_errors() ? validation_errors() : 'An unknown error occured');
        // If we did not create a successful submission, redirect back to the submission page with errors
        $this->load->view("contests/show", $this->data);
    }

    /**
     * Edit a submission
     * @return void
     */
    public function edit() {}

    /**
     * Remove a submission
     * @return void
     */
    public function delete() {}
}

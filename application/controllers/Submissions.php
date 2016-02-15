<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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
        $submissions = $this->contest->submissions($contest_id);
        $this->responder->data(array(
            'submissions' => $submissions
        ))->respond();
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
                $this->responder->fail(
                    (validation_errors() ? validation_errors() : ($this->ion_auth_model->errors() ? $this->ion_auth_model->errors() : 'An unknown error occured'))
                )->code(400)->respond();
                return;
            }
        }

        $this->form_validation->clear();
        if($this->ion_auth->in_group(3))
        {
            $this->responder->fail(
                "Only creators are allowed to submit to contests"
            )->code(403)->respond();
        }

        // Get the contest, and then dynamically change form validation rules, based on the type of the contest
        $contest = $this->contest->get($contest_id);
        if(!$contest)
        {
            $this->responder->fail(
                "We couldnt find the contest you were looking for"
            )->code(404)->respond();
            return;
        }

        if($contest->submission_count >= 50)
        {
            $this->responder->fail(
                "We're sorry, but this contest has reached its submission limit"
            )->code(400)->respond();
            return;
        }

        if($contest->stop_time < date('Y-m-d H:i:s'))
        {
            $this->responder->fail(
                "We're sorry, but this contest has already ended"
            )->code(400)->respond();
        }

        if($logged_in)
        {
            if(!$this->submission_library->userCanSubmit($this->ion_auth->user()->row()->id, $contest->id))
            {
                $this->responder->fail(
                    "You have already submitted to this contest"
                )->code(400)->respond();
            }
            $data = array(
                'owner' => $this->ion_auth->user()->row()->id,
                'contest_id' => $contest->id
            );

            $email_data = array(
                'headline' => $this->input->post('headline'),
                'text' => $this->input->post('text'),
                'email' => ($this->ion_auth->user() ? $this->ion_auth->user()->row()->email : false),
                'contest' => $contest->title,
                'company' => $contest->company->name
            );
        }

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
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();

                    $this->responder->message(
                        "You submission has successfully been created"
                    )->respond();
                    return;
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
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();
                    $this->responder->message(
                        "You submission has successfully been created"
                    )->respond();
                    return;
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
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();
                    $this->responder->message(
                        "You submission has successfully been created"
                    )->respond();
                    return;
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
                    $this->mailer
                        ->to($this->ion_auth->user()->row()->email)
                        ->from('squad@tappyn.com')
                        ->subject('Your submission has successfully been created')
                        ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                        ->send();
                    $this->responder->message(
                        "You submission has successfully been created"
                    )->respond();
                    return;
                }

            break;
        }
        $this->responder->fail(
            (validation_errors() ? validation_errors() : 'An unknown error occured')
        )->code(400)->respond();
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

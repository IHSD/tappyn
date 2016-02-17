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
            'submissions' => $submissions,
            'contest' => $this->contest->get($contest_id)
        ))->respond();
    }

    /**
     * Create a new submission
     *
     * @todo The long switch statement is butt fugly. Lets go back and reimplement once working
     * @return void
     */
    public function create($contest_id)
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail(
                "You must be logged in to create submissions"
            )->code(401)->respond();
            return;
        }

        if($this->ion_auth->in_group(3))
        {
            $this->responder->fail(
                "Only creators are allowed to submit to contests"
            )->code(403)->respond();
            return;
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
            return;
        }
        if(!$this->submission_library->userCanSubmit($this->ion_auth->user()->row()->id, $contest->id))
        {
            $this->responder->fail(
                "You have already submitted to this contest"
            )->code(400)->respond();
            return;
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
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data))) $success = true;
            break;
            case 'google':
                $this->form_validation->set_rules('headline', 'Headline', 'required');
                $this->form_validation->set_rules('text', "text", 'required');
                if($this->form_validation->run() == true)
                {
                    $data['headline'] = $this->input->post('headline');
                    $data['text'] = $this->input->post('text');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data))) $success = true;
            break;
            case 'twitter':
                $this->form_validation->set_rules('text', 'Text', 'required');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data)) && $logged_in) $success = true;
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
                if($this->form_validation->run() == true && ($sid = $this->submission_library->create($data)) && $logged_in) $success = true;

            break;
        }
        if($success)
        {
            $this->mailer
                ->to($this->ion_auth->user()->row()->email)
                ->from('squad@tappyn.com')
                ->subject('Your submission has successfully been created')
                ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                ->send();
            $this->responder->message(
                "You're submission has succesfully been created"
            );
        } else {
            $this->responder->fail(
                (validation_errors() ? validation_errors() : 'An unknown error occured')
            )->code(400)->respond();
        }
    }
}

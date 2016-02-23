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

        if($this->submission_library->create($contest_id, $this->input->post('headline'), $this->input->post('text')))
        {
            $this->responder->message(
                "You're submission has succesfully been created"
            )->respond();
        }
        else {
            $this->responder->fail(
                ($this->submission_library->errors() ? $this->submission_library->errors() : 'An unknown error occured')
            )->code(500)->respond();
        }
    }
}

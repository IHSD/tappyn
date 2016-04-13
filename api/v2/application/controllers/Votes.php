<?php defined("BASEPATH") or exit('No direct script access allowed');

class Votes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('vote', 'submission'));
    }

    public function create()
    {
        $this->form_validation->set_rules('submission_id', "Submission", 'required|exists[submissions.id]|owns[submissions.owner]');
        if($this->form_validation->run() === TRUE)
        {
            $submission = Submission::get($this->input->post('submission_id'));
            $vote = new Vote();
            $data = array(
                VoteFields::SUBMISSION_ID => $this->input->post('submission_id'),
                VoteFields::CONTEST_ID => $submission->{SubmissionFields::CONTEST_ID},
                VoteFields::USER_ID => $this->user->id,
                ViteFields::CREATED_AT => time()
            );
            $vote->setData($data);
            try {
                $vote->save();
            } catch(Exception $e) {
                $this->response->fail($vote->errors())->code(500)->respond();
                return;
            }
            $this->response->data();
        } else {
            $errors = $this->form_validation->error_array();
            $this->response->fail($errors ? reset($errors) : "An unknown error occured");
        }
        $this->response->respond();
    }

    public function delete()
    {

    }
}
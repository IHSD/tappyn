<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('contest', 'submission', 'payout', 'vote'));
        Hook::register_model(array($this->contest, $this->submission, $this->payout, $this->vote));
    }


    public function create($contest_id)
    {
        $this->config->load('form_validation', TRUE);
        $rules = $this->config->item('submission_create');

        // Validate that the contest exists, and also set our validation
        // rules based on the contest platform and objective
        $contest = Contest::get($contest_id);
        if(!$contest)
        {
            $this->response->fail("That contest does not exist")->code(500)->respond();
            return;
        }

        // Check that the contest is accepting submissions
        if(!$contest->accepting_submissions())
        {
            $this->response->fail("That contest is no longer acccepting submissions")->code(500)->respond();
            return;
        }

        if($this->form_validation->run($rules[$contest->platform][$contest->objective]) === TRUE)
        {
            if($submission->save())
            {
                $this->response->data(array('submission' => $submission->data()));
                Hook::trigger('submission_created', array('submission' => $submission, 'user' => $this->user));
            }
            else
            {
                $this->response->fail($submission->errors() ? $submission->errors() : "An unknown error occured")->code(500);
            }
            $this->response->data(array('submission' => $submission->data));
        }
        else
        {
            $this->response->fail(($errors = $this->form_validation->errors_array()) ? reset($errors) : "An unknown error occured");
        }
        $this->response->respond();
    }

    public function update()
    {
        if($this->form_validation->run() === TRUE)
        {
            try {
                $submission->update();
            } catch(Exception $e) {
                $this->response->fail($submission->errors())->code(500)->respond();
                return;
            }
            Hook::trigger("submission_updated", array());
            $this->response->data(array('submission' => $submission));
        }
        else
        {
            $this->response->fail(($errors = $this->form_validation->errors_array()) ? reset($errors) : 'An unknown error occured');
        }
        $this->response->respond();
    }

    public function set_as_winner()
    {

    }

    public function share()
    {
        Hook::trigger('submission_shared');
    }

    public function rating()
    {
        Hook::trigger('submission_rated');
    }

    public function delete()
    {
        Hook::trigger('submission_deleted');
    }
}

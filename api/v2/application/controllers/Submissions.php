<?php defined("BASEATH") or exit('No direct script access allowed');

class Submissions extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('contest', 'submission', 'payout'));
    }

    public function set_as_winner()
    {

    }
    
    public function create()
    {
        if($this->form_validation->run('submissions:create') === TRUE)
        {
            try {
                $submission->save();
            } catch(Exception $e) {
                $this->response->fail($submission->errors())->code(500)->respond();
                return;
            }
            Hook::trigger('submission_created', array());
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
        if($this->form_validation->run('submissions:updated') === TRUE)
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

    public function delete()
    {

    }
}

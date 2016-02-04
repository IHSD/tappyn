<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/navbar');
        $this->load->model('submission');
        $this->load->model('contest');
    }

    /**
     * Get all submissions for a contest
     * @param  int $contest_id
     * @return void
     */
    public function index($contest_id)
    {
        $submissions = $this->contest->submissions($contest_id);
    }

    /**
     * Create a new submission
     * @return void
     */
    public function create($contest_id)
    {
        if($this->ion_auth->logged_in())
        {
            $this->session->set_flashdata('error', 'You have to be logged in to create a submission');
            redirect("contests/show{$contest_id}");
        }
        
        $this->form_validation->set_rules('start_time', 'start_time', 'required');
        $this->form_validation->set_rules('stop_time', 'stop_time', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('submission_limit', 'submission_limit', 'required');
        $this->form_validation->set_rules('prize', 'prize', 'required');
        $this->form_validation->set_rules('objective', 'objective', 'required');
        $this->form_validation->set_rules('platform', 'platform', 'required');

        if($this->form_validation->run() == true)
        {
            // Do some preliminary formatting
        }
        if($this->form_validation->run() == true && ($cid = $this->contest->create(array())))
        {
            $this->session->set_flashdata('message', $this->contest->messages());
            redirect("contests/show/{$cid}");
        }
        else
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : array('An unknown error occured')));
            $this->data['start_time'] = array(
                'name' => 'start_time',
                'id' => 'start_time',
                'type' => 'text',
                'value' => $this->form_validation->set_value('start_time')
            );
            $this->data['stop_time'] = array(
                'name' => 'stop_time',
                'id' => 'stop_time',
                'type' => 'text',
                'value' => $this->form_validation->set_value('stop_time')
            );
            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('title')
            );
            $this->data['submission_limit'] = array(
                'name' => 'submission_limit',
                'id' => 'submission_limit',
                'type' => 'text',
                'value' => $this->form_validation->set_value('submission_limit')
            );
            $this->data['prize'] = array(
                'name' => 'prize',
                'id' => 'prize',
                'type' => 'text',
                'value' => $this->form_validation->set_value('prize')
            );
            $this->data['objective'] = array(
                'name' => 'objective',
                'id' => 'objective',
                'type' => 'text',
                'value' => $this->form_validation->set_value('objective')
            );
            $this->data['platform'] = array(
                'name' => 'platform',
                'id' => 'platform',
                'type' => 'text',
                'value' => $this->form_validation->set_value('platform')
            );

            $this->load->view('contests/create', $this->data);
        }
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

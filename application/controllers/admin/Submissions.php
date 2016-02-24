<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(1))
        {
            $this->session->set_flashdata('error', "You do not have permission to access that area");
            redirect('auth/login', 'refresh');
        }
        $this->load->view('templates/navbar');
        $this->load->model('submission');
        $this->load->library('payout');
        $this->load->model('contest');
    }

    public function index()
    {
        $config['per_page'] = 20;
        $where = array();
        if($this->input->get('user')) $where['owner'] = $this->input->get('user');
        $config['base_url'] = base_url().'admin/submissions/index';
        $config['total_rows'] = $this->submission->count($where);
        $this->pagination->initialize($config);

        $offset = $this->input->get('per_page') ? (($this->input->get('per_page') * $config['per_page']) - $config['per_page']) : 0;
        $this->submission->limit($config['per_page']);
        $this->submission->offset($offset);

        if($this->input->get('sort_by') && $this->input->get("sort_dir"))
        {
            $this->submission->order_by($this->input->get('sort_by'), $this->input->get('sort_dir'));
        } else if($this->input->get('sort_by'))
        {
            $this->submission->order_by($this->input->get('sort_by'));
        }
        // Parse query string for possible query params
        $this->data['pagination_links'] = $this->pagination->create_links();
        $submissions = $this->submission->fetch()->result();

        foreach($submissions as $submission)
        {
            $submission->contest = $this->contest->get($submission->contest_id);
            $submission->payout = $this->payout->fetch(array('submission_id', $submission->id));
        }

        $this->data['submissions'] = $submissions;
        $this->load->view('admin/users/submissions', $this->data);
    }

    public function show()
    {

    }

    public function edit()
    {
        $this->form_validation->set_rules('submission_id', 'Submission ID', 'required');
        $this->form_validation->set_rules('headline', 'Headline', 'required');
        $this->form_validation->set_rules('text', 'Text', 'required');
        if($this->form_validation->run() == true)
        {

        }
        if($this->form_validation->run() === true &&
            $this->submission->update($this->input->post('submission_id'), array(
                'headline' => $this->input->post('headline'),
                'text' => $this->input->post("text")
            )))
        {
            $this->session->set_flashdata('message', "Submission successfully updated");
        } else {
            $this->session->set_flashdata('error', (validation_errors() ? validation_errors() : ($this->submission->errors() ? $this->submission->errors() : "An unknown error occured")));
        }
        redirect("admin/users/submissions/{$this->input->post('user_id')}", 'refresh');
    }

    public function delete()
    {

    }
}

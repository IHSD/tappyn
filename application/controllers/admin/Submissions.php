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
            $submission->payout = $this->payout->fetch(array('id' => $submission->payout_id));
        }

        $this->data['submissions'] = $submissions;
        $this->load->view('admin/submissions/index', $this->data);
    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function delete()
    {

    }
}

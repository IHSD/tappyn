<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(1))
        {
            $this->session->set_flashdata('error', "You do not have permission to access that area");
            redirect('auth/login', 'refresh');
        }
        $this->load->view('templates/admin_navbar');
        $this->load->view('templates/navbar');
        $this->load->model('contest');
        $this->load->model('user');
    }

    public function index()
    {
        $config['base_url'] = base_url().'admin/contests/index';
        $config['total_rows'] = $this->contest->count();
        $config['per_page'] = 20;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 3;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);

        // Set our limit and offset
        $offset = $this->input->get('per_page') ? (($this->input->get('per_page') * $config['per_page']) - $config['per_page']) : 0;
        $this->contest->limit($config['per_page']);
        $this->contest->offset($offset);

        // Set ordering
        if($this->input->get('sort_by') && $this->input->get("sort_dir"))
        {
            $this->contest->order_by($this->input->get('sort_by'), $this->input->get('sort_dir'));
        } else if($this->input->get('sort_by'))
        {
            $this->contest->order_by($this->input->get('sort_by'));
        }
        // Parse query string for possible query params
        $this->data['pagination_links'] = $this->pagination->create_links();
        $contests = $this->contest->fetch();
        if($contests)
        {
            $contests = $this->contest->result();
            foreach($contests as $contest)
            {
                //$company = $this->user->where('users.id', $contest->owner)->fetch();
                $contest->company = $company->row();
                $contest->submission_count = $this->contest->submissionsCount($contest->id);
            }
            $this->data['contests'] = $contests;
        } else {
            $this->data['error'] = "An unknown error occured";
        }

        $this->load->view('admin/contests/index', $this->data);
    }

    public function show($cid)
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function delete()
    {

    }

    public function suspend()
    {

    }

    public function respend()
    {

    }
}

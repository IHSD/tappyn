<?php defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(1))
        {
            redirect('/','refresh');
        }
        $this->load->view('templates/admin_navbar');
        $this->load->view('templates/navbar');
        $this->load->model('user');
        $this->load->model('submission');
        $this->load->library('payout');
        $this->load->model('contest');
    }

    public function index()
    {
        // Initialize pagination
        $config['base_url'] = base_url().'admin/users/index';
        $config['total_rows'] = $this->user->count();
        $config['per_page'] = 20;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 3;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);

        // Set our limit and offset
        $offset = $this->input->get('per_page') ? (($this->input->get('per_page') * $config['per_page']) - $config['per_page']) : 0;
        $this->user->limit($config['per_page']);
        $this->user->offset($offset);

        // Set ordering
        if($this->input->get('sort_by') && $this->input->get("sort_dir"))
        {
            $this->user->order_by($this->input->get('sort_by'), $this->input->get('sort_dir'));
        } else if($this->input->get('sort_by'))
        {
            $this->user->order_by($this->input->get('sort_by'));
        }
        // Parse query string for possible query params
        $this->data['pagination_links'] = $this->pagination->create_links();
        $this->data['users'] = $this->user->fetch()->result();
        foreach ($this->data['users'] as $k => $user)
        {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            $this->data['users'][$k]->profile = $this->user->profile($user->id);
        }
        $this->load->view('admin/users/index', $this->data);
    }

    public function show($uid)
    {
        $user = $this->ion_auth->user($uid)->row();
        $user->profile = $this->user->profile($uid);
        $this->data['user'] = $user;
        $this->load->view('admin/users/show', $this->data);
    }

    public function search()
    {
        if(is_numeric($this->input->post('user')))
        {
            // Search by ID
            $type = "UID";
            $where = array('id' => $this->input->post('user'));
        }
        else
        {
            $type = "Email";
            $where = array('email' => $this->input->post('user'));
        }
        $user = $this->user->where($where)->fetch();
        if($user && $user->num_rows() > 0)
        {
            redirect('admin/users/show/'.$user->row()->id, 'refresh');
        }
        else {
            $this->session->set_flashdata('error', "We could not find user with {$type} of {$this->input->post('user')}");
            redirect("admin/users/index", 'refresh');
        }
    }

    public function submissions($uid)
    {
        $user = $this->ion_auth->user($uid)->row();
        $user->profile = $this->user->profile($uid);
        $this->data['user'] = $user;
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

    public function transfers()
    {
        $this->load->library('stripe/stripe_transfer_library');
    }

    public function account($uid)
    {
        $this->load->library('stripe/stripe_account_library');
    }
}

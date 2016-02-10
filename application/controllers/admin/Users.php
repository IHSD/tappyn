<?php defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/admin_navbar');
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(1))
        {
            $this->session->set_flashdata('error', 'You must be an administrator to access this area');
            redirect("auth/login");
        }
        $this->load->model('user');
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
        }
        $this->load->view('admin/users/index', $this->data);
    }
}

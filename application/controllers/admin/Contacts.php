<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contacts extends CI_Controller
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
        $this->load->model('contact');
    }

    public function index()
    {
        $where = array();
        $config['base_url'] = base_url().'admin/contacts/index';
        $config['total_rows'] = $this->contact->count($where);
        $config['per_page'] = 20;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 3;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);

        // Set our limit and offset
        $offset = $this->input->get('per_page') ? (($this->input->get('per_page') * $config['per_page']) - $config['per_page']) : 0;
        $this->contact->limit($config['per_page']);
        $this->contact->offset($offset);

        // Set ordering
        if($this->input->get('sort_by') && $this->input->get("sort_dir"))
        {
            $this->contact->order_by($this->input->get('sort_by'), $this->input->get('sort_dir'));
        } else if($this->input->get('sort_by'))
        {
            $this->contact->order_by($this->input->get('sort_by'));
        }
        // Parse query string for possible query params
        $this->data['pagination_links'] = $this->pagination->create_links();
        $this->data['contacts'] = $this->contact->fetch()->result();

        $this->load->view('admin/contacts/index', $this->data);
    }

    public function show()
    {

    }

    public function update()
    {

    }
}

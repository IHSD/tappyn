<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            $this->session->set_flashdata('error', 'You dont have permission to access this area');
            redirect('contests/index', 'refresh');
        }
        $this->load->view('templates/navbar');
        $this->config->load('secrets');
        $this->load->model('payout_model');
        $this->data['publishable_key'] = $this->config->item('stripe_api_publishable_key');

    }

    public function index()
    {
        // Initialize pagination
        $where = array();
        if(isset($this->input->get('claimed'))) $where['claimed'] = $this->input->get('claimed');
        if(isset($this->input->get('created_before'))) $where['created_at <'] = $this->input->get('created_before');
        if(isset($this->input->get('created_after'))) $where['created_after >'] = $this->input->get('created_after');
        if(isset($this->input->get('submission_id'))) $where['submission_id'] = $this->input->get('submission_id');
        if(isset($this->input->get('user_id'))) $where['user_id'] = $this->input->get('user_id');
        if(isset($this->input->get('account_id'))) $where['account_id'] = $this->input->get('account_id');
        if(isset($htis->input->get('transfer_id'))) $where['transfer_id'] = $this->input->get('transfer_id');
        if(isset($this->input->get('account_type'))) $where['account_type'] = $this->input->get('account_type');
        $config['base_url'] = base_url().'admin/payments/index';
        $config['total_rows'] = $this->payout_model->count($where);
        $config['per_page'] = 20;
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 3;
        $config['page_query_string'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $this->pagination->initialize($config);

        // Set our limit and offset
        $offset = $this->input->get('per_page') ? (($this->input->get('per_page') * $config['per_page']) - $config['per_page']) : 0;
        $this->payout_model->where($where);
        $this->payout_model->limit($config['per_page']);
        $this->payout_model->offset($offset);

        // Set ordering
        if($this->input->get('sort_by') && $this->input->get("sort_dir"))
        {
            $this->payout_model->order_by($this->input->get('sort_by'), $this->input->get('sort_dir'));
        } else if($this->input->get('sort_by'))
        {
            $this->payout_model->order_by($this->input->get('sort_by'));
        }
        // Parse query string for possible query params
        $this->data['pagination_links'] = $this->pagination->create_links();
        $this->data['payouts'] = $this->payout_model->fetch()->result();
        foreach ($this->data['payouts'] as $k => $payout)
        {

        }
        $this->load->view('admin/payouts/index', $this->data);
    }

    public function update()
    {

    }

    public function test()
    {
        $this->load->view('admin/test', $this->data);
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Companies extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('/', 'refresh');
        }
        $this->load->view('templates/navbar');
        $this->load->model('contest');
    }

    public function contests($uid)
    {
        $user = $this->ion_auth->user($uid)->row();
        $user->profile = $this->user->profile($uid);
        $this->data['user'] = $user;
        if($this->contest->where('owner', $uid)
                      ->order_by('created_at', 'desc')
                      ->fetch())
        {
            $this->data['contests'] = $this->contest->result();
        } else {
            $this->session->set_flashdata('error', $this->contest->errors() ? $this->contest->errors() : "An unknown error occured");
        }
        $this->load->view('admin/companies/contests', $this->data);
    }

    public function account_details($uid)
    {
        $this->load->library('stripe/stripe_charge_library');
        $this->load->library('stripe/stripe_customer_library');
        $user = $this->ion_auth->user($uid)->row();
        $user->profile = $this->user->profile($uid);
        $this->data['user'] = $user;
        $customer_id = $this->db->select('*')->from('stripe_customers')->where('user_id', $uid)->get();
        if($customer_id === FALSE)
        {
            $this->data['customer'] = FALSE;
        } else {
            $this->data['customer'] = $this->stripe_customer_library->fetch($customer_id->row()->customer_id);
        }
        $this->load->view('admin/companies/account', $this->data);

    }

    public function payment_history($uid)
    {
        $this->load->library('stripe/stripe_customer_library');
        $user = $this->ion_auth->user($uid)->row();
        $user->profile = $this->user->profile($uid);
        $this->data['user'] = $user;
        $customer_id = $this->db->select('*')->from('stripe_customers')->where('user_id', $uid)->get();
        if($customer_id === FALSE)
        {
            $this->data['customer'] = FALSE;
            $this->data['charges'] = FALSE;
        } else {
            $this->data['customer'] = $this->stripe_customer_library->fetch($customer_id->row()->customer_id);
            $this->data['charges'] = $this->stripe_customer_library->charges(array('customer' => $customer_id->row()->customer_id));
        }
        $this->load->view('admin/companies/history', $this->data);

    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Companies extends CI_Controller
{
    protected $stripe_customer_id = false;

    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(3))
        {
            $this->session->set_flashdata('error', 'You must be a company to access this area');
            redirect('contests/index', 'refresh');
        }
        $this->load->view('templates/navbar');
        $this->load->model('company');
        $this->config->load('secrets');
        $this->data['publishable_key'] = $this->config->item('stripe_api_publishable_key');
        $this->load->library('stripe/stripe_customer_library');
        $this->stripe_customer_id = $this->company->payment_details($this->ion_auth->user()->row()->id);
    }

    public function accounts()
    {

        // Check if we have to process a form in any way
        if($this->input->post('stripeToken') && $this->stripe_customer_id)
        {
            // We're going to add a new payment method to the customer
            if($this->stripe_customer_library->addPaymentSource($this->stripe_customer_id, $this->input->post('stripeToken')))
            {
                $this->data['message'] = "Your payment method was successfully added";
            } else {
                $this->data['error'] = ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : "An unknown error occured");
            }
        }
        else if($this->input->post('stripeToken'))
        {
            // We're going to create a new customer altogether
            if($customer = $this->stripe_customer_library->create($this->ion_auth->user()->row()->id, $this->input->post('stripeToken')))
            {
                // Create row in database for the user
                $this->stripe_customer_id = $customer->id;
            } else {
                $this->data['error'] = $this->stripe_customer_library->errors();
            }
        }
        $this->data['customer'] = NULL;

        // After any proccessing, we fetch the updated customer to then show the user
        if($this->stripe_customer_id)
        {
            $this->data['customer'] = $this->stripe_customer_library->fetch($this->stripe_customer_id);
        }
        $this->load->view('companies/accounts.php', $this->data);
    }

    public function removeCard()
    {
        if(!$stripe_customer_id)
        {
            $this->session->set_flashdata('You havent created a payment method with us yet');
            redirect('companies/accounts', 'refresh');
        }
        if($this->input->post('source_id'))
        {
            $data = array(
                'default_source' => $this->input->post('source_id')
            );

            if($this->stripe_customer_library->update($stripe_customer_id,$data))
            {
                $this->session->set_flashdata('message', 'Default payment option successfully updated');
                redirect('companies/accounts', 'refresh');
            } else {
                $this->session->set_flashdata('error', ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : 'An unknown error occured'));
                redirect('companies/accounts', 'refresh');
            }
        } else {
            $this->session->set_flashdata('You must provide a payment option to remove');
            redirect('companies/accounts', 'refresh');
        }
    }

    public function setAsDefault()
    {
        if(!$stripe_customer_id)
        {
            $this->session->set_flashdata('You havent created a payment method with us yet');
            redirect('companies/accounts', 'refresh');
        }
    }

    public function addCard()
    {
        // user has submitted the form for adding a
        if($this->input->post('stripeToken'))
        {

        }
    }
}

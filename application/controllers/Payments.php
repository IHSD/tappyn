<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/navbar');
    }

    public function test()
    {
        $this->load->library('stripe/stripe_account_library');
        $this->stripe_account_library->create();
    }

    public function transfer()
    {

        $this->load->library('stripe/Stripe_transfer_library');
        $this->stripe_transfer_library->create('acct_17dQHYCDeYXQJ6QQ',2,5000);
    }

    public function balance()
    {
        $this->load->library('stripe/Stripe_transfer_library');
        if($this->input->post('stripeToken'))
        {
            $this->stripe_transfer_library->balance($this->input->post('stripeToken'));
        }
        $this->data['balance'] = $this->stripe_transfer_library->retrieve();
        $this->load->view('auth/payments/test', $this->data);
    }

    public function account()
    {
        $this->load->library('stripe/Stripe_account_library');
        die(json_encode($this->stripe_account_library->get()));
    }
}

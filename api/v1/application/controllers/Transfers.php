<?php defined("BASEPATH") or exit('No direct script access allowed');

class Transfers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail(array(
                'error' => "You must be logged in to access this area"
            ))->code(401)->respond();
            return;
        }
        $this->load->model('user');
        $this->load->library('stripe/stripe_transfer_library');
        $this->stripe_account_id = $this->user->account($this->ion_auth->user()->row()->id);
    }

    public function index()
    {
        \Stripe\Stripe::setApiKey('sk_test_mrVtwioUR2Fq1QV6yexrrctv');
        $transfers = $this->stripe_transfer_library->index();
        if($transfers !== FALSE)
        {
            $this->responder->data(array(
                'transfers' => $transfers
            ))->respond();
        } else {
            $this->responder->fail(
                ($this->stripe_transfer_library->errors() ? $this->stripe_transfer_library->errors() : array('error' => 'There was an error fetching your transfers'))
            )->code(500)->respond();
        }
    }

    public function show($id)
    {
        $transfer = $this->stripe_transfer_library->retrieve($id);
        if($transfer)
        {
            $this->responder->data(array(
                'transfer' => $transfer
            ))->respond();
        } else {
            $this->responder->fail(array(
                'error' => $this->stripe_transfer_library->errors()
            ))->code(500)->respond();
        }
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Transfers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('/', 'refresh');
        }
        if($this->input->post('account_id'))
        {
            $account = $this->user->accountDetails($this->input->post('account_id'));
            //echo json_encode($account);
        } else {
            echo "NO account ID Provided";
        }
        $this->load->library('stripe/stripe_transfer_library');
    }

    public function index()
    {
        $transfer = $this->stripe_transfer_library->retrieve($this->input->post('transfer_id'));
        if($transfer)
        {
            $this->responder->data(array(
                'transfer' => $transfer
            ))->respond();
        } else {
            $this->responder->fail($this->stripe_transfer_library->errors())->code(500)->respond();
        }
    }
}

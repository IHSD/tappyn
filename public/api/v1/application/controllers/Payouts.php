<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payouts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('payout');
        $this->load->library('stripe/stripe_account_library');
        $this->load->library('stripe/stripe_transfer_library');
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in to access this area.")->code(401)->respond();
            exit();
        }
    }

    public function index()
    {
        $payouts = $this->payout->fetch(array('user_id' => $this->ion_auth->user()->row()->id));
        $this->responder->data(
            array(
                'payouts' => $payouts
            )
        )->respond();
    }

    public function show($pid)
    {
        $payout = $this->payout->get($pid);
        if($payout)
        {
            $payout->transfer = $this->stripe_transfer_library->retrieve($payout->transfer_id);
            $this->templater->data(array(
                'payout' => $payout
            ))->respond();
        } else {
            $this->responder->fail("We couldn't find the payout you were looking for.")->code(500)->respond();
        }
    }

    /**
     * Claim a users payout
     * @return [type] [description]
     */
    public function claim($id)
    {
        // Check that the payout exists
        $payout = $this->payout->get($id);
        if(!$payout)
        {
            $this->responder->fail("That payout does not exist.")->code(500)->respond();
            return;
        }
        // And that it hasnt been claimed
        if($payout->claimed == 1)
        {
            $this->responder->fail("That payout has already been claimed."
            )->code(500)->respond();
            return;
        }
        // Chekc that have set up their accounts alread'
        $stripe_account = $this->db->select('*')->where('user_id', $this->ion_auth->user()->row()->id)->limit(1)->get('stripe_accounts');
        if(!$stripe_account || $stripe_account->num_rows() == 0)
        {
            $this->responder->fail("You need to set up your account first."
            )->code(500)->respond();
            return;
        }
        $account = $this->stripe_account_library->get($stripe_account->row()->account_id);
        if(!$account)
        {
            $this->responder->fail("You need to set up your account first."
            )->code(500)->respond();
            return;
        }
        // check that transfers are enabled
        if(!$account->transfers_enabled)
        {
            $this->responder->fail("You still need to set up some account details."
            )->code(500)->respond();
            return;
        }
        // OK, now we can process the requested transfer
        if($transfer = $this->stripe_transfer_library->create($account->id, $payout->contest_id, $payout->amount, $payout->id))
        {
            $this->payout->update($payout->id, array('pending' => 0, 'claimed' => 1, 'transfer_id' => $transfer->id, 'account_id' => $account->id));
            $this->responder
                ->message("Transfer {$transfer->id} successfully created for {$transfer->amount}. Please allow 3-5 business days for funds to be available.")
                ->data(array('payout' => $this->payout->get($id)))
                ->respond();
                $this->analytics->track(array(
                    'event_name' => "payout_claimed",
                    'object_type' => "payout",
                    'object_id' => $payout->id
                ));
        } else {
            $this->responder->fail(
                validation_errors() ? validation_errors() : ($this->stripe_transfer_library->errors() ? $this->stripe_transfer_library->errors() : array('error' => 'An unknown error occured'))
            )->code(500)->respond();
        }
    }
}

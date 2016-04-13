<?php defined("BASEPATH") or exit('No direct script access allowed');

class Stripe_transfer extends MY_Model
{
    public $table = 'stripe_transfers';
    public $order_by = 'stripe_transfers.created_at';
    public $order_dir = 'desc';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Save our transfer to the DB
     * @param  object $transfer Stripe Transfer Object
     * @return booleantps
     */
    public function save($transfer, $payout_id)
    {
        return $this->db->insert('stripe_transfers', array(
            'transfer_id' => $transfer->id,
            'destination' => $transfer->destination,
            'description' => $transfer->description,
            'amount'      => $transfer->amount,
            'created_at'  => $transfer->created,
            'payout_id'   => $payout_id
        ));
    }
}

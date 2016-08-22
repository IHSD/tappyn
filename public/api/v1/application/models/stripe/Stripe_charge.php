<?php defined("BASEPATH") or exit('No direct script access allowed');

class Stripe_charge extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function save($charge, $uid, $contest_id)
    {
        return $this->db->insert('stripe_charges', array(
            'charge_id'   => $charge->id,
            'contest_id'  => $contest_id,
            'amount'      => $charge->amount,
            'captured'    => 1,
            'created'     => $charge->created,
            'currency'    => $charge->currency,
            'description' => $charge->description,
            'source'      => $charge->source->id,
            'status'      => $charge->status,
            'customer'    => $uid,
        ));
    }

    public function create($customer_id, $amount, $description)
    {
        try {
            $charge = \Stripe\Charge::create(array(
                'amount'      => $amount,
                'currency'    => 'usd',
                'source'      => $customer->id,
                'description' => $description,
            ));
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        $this->insert($charge);
        return $charge->id;
    }

    public function insert($charge)
    {
        $this->db->insert('stripe_charges', array(

        ));
    }
}

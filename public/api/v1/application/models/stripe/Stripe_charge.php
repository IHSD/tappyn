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
        error_log("Calling save()");
        return $this->db->insert('stripe_charges', array(
            'charge_id' => $charge->id,
            'contest_id' => $contest_id,
            'amount' => $charge->amount,
            'captured' => 1,
            'created' => $charge->created,
            'currency' => $charge->currency,
            'description' => $charge->description,
            'source' => $charge->source->id,
            'status' => $charge->status
        ));
    }

    public function create($customer_id, $amount, $description)
    {
        error_log("Calling create()");
        try{
            $charge = \Stripe\Charge::create(array(
                'amount'        => $amount,
                'currency'      => 'usd',
                'source'        => $customer->id,
                'description'   => $description
            ));
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        $this->insert($charge);
        return $charge->id;
    }

    public function insert($charge)
    {
        error_log("Calling insert()");
        $this->db->insert('stripe_charges', array(

        ));
    }
}

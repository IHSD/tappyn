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

    public function create($customer_id, $amount, $description)
    {
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
        $this->db->insert('stripe_charges', array(

        ));
    }
}

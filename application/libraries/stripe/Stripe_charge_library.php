<?php defined("BASEPATH") or exit('No direct script access allowed');

class stripe_charge_library
{
    protected $errors = FALSE;
    public function __construct()
    {
        $this->config->load('secrets');
        $this->api_key = $this->config->item('stripe_api_key');
        \Stripe\Stripe::setApiKey($this->api_key);
        $this->load->library('stripe/stripe_transfer_library');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __call($method, $arguments)
    {
        if(!method_exists($this->stripe_charge, $method))
        {
            throw new Exception("Call to undefined method Stripe_charge::{$method}()");
        }
        return call_user_func_array( array($this->stripe_charge, $method), $arguments);
    }

    public function create($contest_id, $token = NULL, $customer_id = NULL, $source_id = NULL, $amount = 9999, $metadata = array())
    {
        $data = array(
            'amount' => $amount,
            'currency' => 'usd',
            'description' => "Charge for contest {$contest_id}",
            'metadata' => $metadata
        );

        if(is_null($token))
        {
            if(is_null($customer_id) && is_null($source_id))
            {
                $this->errors = "You have to provide a new payment method, or a customer and selected payment source";
                return false;
            }
            $data['source'] = $source_id;
            $data['customer'] = $customer_id;
        } else {
            $data['source'] = $token;
        }

        try {
            $charge = \Stripe\Charge::create($data);
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        $this->stripe_charge->save($charge, $this->ion_auth->user()->row()->id, $contest_id);
        return $charge;

    }

    public function errors()
    {
        return $this->errors;
    }

    public function retrieve($charge_id)
    {

    }
}

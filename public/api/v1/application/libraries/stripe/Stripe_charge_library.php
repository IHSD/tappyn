<?php defined("BASEPATH") or exit('No direct script access allowed');

class stripe_charge_library
{
    protected $errors = false;
    public function __construct()
    {
        $this->config->load('secrets');
        $this->api_key = $this->config->item('stripe_api_key');
        \Stripe\Stripe::setApiKey($this->api_key);
        $this->load->library('stripe/stripe_transfer_library');
        $this->load->model('stripe/stripe_charge');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __call($method, $arguments)
    {
        if (!method_exists($this->stripe_charge, $method)) {
            throw new Exception("Call to undefined method Stripe_charge::{$method}()");
        }
        return call_user_func_array(array($this->stripe_charge, $method), $arguments);
    }

    public function create($contest_id, $token = null, $customer_id = null, $source_id = null, $amount = 4999, $metadata = array(), $description = '')
    {
        if (!$description) {
            $description = "Charge for contest {$contest_id}";
        }

        $data = array(
            'amount'      => $amount,
            'currency'    => 'usd',
            'description' => $description,
            'metadata'    => $metadata,
        );

        if (is_null($token)) {
            if (is_null($customer_id) && is_null($source_id)) {
                $this->errors = "You have to provide a new payment method, or a customer and selected payment source";
                return false;
            }
            $data['source']   = $source_id;
            $data['customer'] = $customer_id;
        } else {
            $data['source'] = $token;
        }

        try {
            $charge = \Stripe\Charge::create($data);
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        error_log("Charge successful. Attempting to save the charge to DB");
        $this->stripe_charge->save($charge, $this->ion_auth->user()->row()->id, $contest_id);
        return $charge;

    }

    public function errors()
    {
        return $this->errors;
    }

    public function retrieve($charge_id)
    {
        try {
            $charge = \Stripe\Charge::retrieve($charge_id);
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
            error_log(json_encode($this->errors));
            return false;
        }
        return $charge;
    }

    public function search_by_uid($uid)
    {
        return $this->db->select('id,contest_id,amount,created,description')->from('stripe_charges')->where('customer', $uid)->order_by('id', 'DESC')->get()->result();
    }
}

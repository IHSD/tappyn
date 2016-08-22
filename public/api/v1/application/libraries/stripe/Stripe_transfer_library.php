<?php defined("BASEPATH") or exit('No direct script access allowed');

class Stripe_transfer_library
{
    protected $api_key;
    public function __construct()
    {
        $this->config->load('secrets');
        $this->api_key = $this->config->item('stripe_api_key');
        \Stripe\Stripe::setApiKey($this->api_key);
        $this->load->model('stripe/stripe_transfer');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __call($method, $arguments)
    {
        if(!method_exists($this->stripe_transfer, $method))
        {
            throw new Exception("Call to undefined method Stripe_transfer::{$method}()");
        }
        return call_user_func_array( array($this->stripe_account, $method), $arguments);
    }

    public function setApiKey($api_key)
    {
        \Stripe\Stripe::setApiKey($api_key);
    }

    public function create($account_id, $contest_id, $amount, $payout_id)
    {
        try {
            $transfer = \Stripe\Transfer::create(array(
                'amount' => $amount,
                'currency' => 'usd',
                'destination' => $account_id,
                'description' => "Payout for contest {$contest_id}"
            ));
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        $this->stripe_transfer->save($transfer, $payout_id);
        return $transfer;
    }

    public function retrieve($tid)
    {
        try {
            $transfer = \Stripe\Transfer::retrieve($tid);
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        return $transfer;
    }

    public function balance($token)
    {
        try {
            $charge = \Stripe\Charge::create(array(
                'amount' => 1000000,
                'currency' => 'usd',
                'source' => $token,
                'description' => "Test transacetion"
            ));
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        return $charge;
    }

    public function index()
    {
        try {
            $transfers = \Stripe\Transfer::all();
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        return $transfers;
    }
    public function errors()
    {
        return $this->errors;
    }
}

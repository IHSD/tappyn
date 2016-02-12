<?php defined("BASEPATH") or exit('No direct script access allowed');

class Stripe_account_library
{
    protected $api_key;
    protected $errors = FALSE;
    public function __construct()
    {
        $this->config->load('secrets');
        $this->api_key = $this->config->item('stripe_api_key');
        \Stripe\Stripe::setApiKey($this->api_key);
        $this->load->model('stripe/stripe_account');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function __call($method, $arguments)
    {
        if(!method_exists($this->stripe_account, $method))
        {
            throw new Exception("Call to undefined method Stripe_account::{$method}()");
        }
        return call_user_func_array( array($this->stripe_account, $method), $arguments);
    }

    /**
     * Create a Stripe Account for a user
     * @param  string $email User Email
     * @return object $account
     */
    public function create($email)
    {
        try{
            $account = \Stripe\Account::create(array(
                "managed" => true,
                "country" => "US",
                "email" => $email
            ));
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        return $this->db->insert('stripe_accounts', array(
            'account_id' => $account->id,
            'user_id' => $this->ion_auth->user()->row()->id,
            'publishable_key' => $account->keys->publishable,
            'secret_key' => $account->keys->secret
        ));
    }

    public function update($id, $data)
    {
        echo json_encode($data);
        try {
            $account = \Stripe\Account::retrieve($id);
            foreach($data as $key => $value)
            {
                switch($key) {
                    case 'legal_entity.first_name':
                        $account->legal_entity->first_name = $value;
                        break;
                    case 'legal_entity.last_name':
                        $account->legal_entity->last_name = $value;
                        break;
                    case 'legal_entity.dob.month':
                        $account->legal_entity->dob->month = $value;
                        break;
                    case 'legal_entity.dob.day':
                        $account->legal_entity->dob->day = $value;
                        break;
                    case 'legal_entity.dob.year':
                        $account->legal_entity->dob->year = $value;
                        break;
                    default:
                        $account->$key = $value;
                    }
            }
            echo json_encode($account);
            $account->save();
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        return true;
    }

    public function get($aid)
    {
        try {
            $account = \Stripe\Account::retrieve($aid);
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        return $account;
    }

    public function errors()
    {
        return $this->errors;
    }
}

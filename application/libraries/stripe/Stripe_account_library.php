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
    public function create($email, $data)
    {
        $account_data = array(
            "managed" => true,
            "email" => $email,
            "tos_acceptance" => array(
                "ip" => $_SERVER['REMOTE_ADDR'],
                "date" => time(),
                "user_agent" => $_SERVER['HTTP_USER_AGENT']
            ),
            "legal_entity" => array(
                'type' => 'individual'
            )
        );
        foreach($data as $key => $value)
        {
            switch($key) {
                case 'legal_entity.first_name':
                    $account_data['legal_entity']['first_name'] = $value;
                    break;
                case 'legal_entity.last_name':
                    $account_data['legal_entity']['last_name'] = $value;
                    break;
                case 'legal_entity.dob.month':
                    $account_data['legal_entity']['dob']['month'] = $value;
                    break;
                case 'legal_entity.dob.day':
                    $account_data['legal_entity']['dob']['day'] = $value;
                    break;
                case 'legal_entity.dob.year':
                    $account_data['legal_entity']['dob']['year'] = $value;
                    break;
                case 'legal_entity.address.line1':
                    $account_data['legal_entity']['address']['line1'] = $value;
                    break;
                case 'legal_entity.address.line2':
                    $account_data['legal_entity']['address']['line2'] = $value;
                    break;
                case 'legal_entity.address.state':
                    $account_data['legal_entity']['address']['state'] = $value;
                    break;
                case 'legal_entity.address.postal_code':
                    $account_data['legal_entity']['address']['postal_code'] = $value;
                    break;
                case 'legal_entity.address.city':
                    $account_data['legal_entity']['address']['city'] = $value;
                    break;
                case 'legal_entity.ssn_last_4':
                    $account_data['legal_entity']['ssn_last_4'] = $value;
                default:
                    $account_data[$key] = $value;
            }
            error_log(json_encode($account_data));
        }

        try{
            $account = \Stripe\Account::create($account_data);
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }

        $this->db->insert('stripe_accounts', array(
            'account_id' => $account->id,
            'user_id' => $this->ion_auth->user()->row()->id,
            'publishable_key' => $account->keys->publishable,
            'secret_key' => $account->keys->secret,
            'transfers_enabled' => false,
            'created_at' => time()
        ));
        return $account;
    }

    public function update($id, $data)
    {
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
                    case 'legal_entity.address.line1':
                        $account->legal_entity->address->line1 = $value;
                        break;
                    case 'legal_entity.address.line2':
                        $account->legal_entity->address->line2 = $value;
                        break;
                    case 'legal_entity.address.city':
                        $account->legal_entity->address->city = $value;
                        break;
                    case 'legal_entity.address.state':
                        $account->legal_entity->address->state = $value;
                        break;
                    case 'legal_entity.address.postal_code':
                        $account->legal_entity->address->postal_code = $value;
                        break;
                    case 'legal_entity.ssn_last_4':
                        $account->legal_entity->ssn_last_4 = $value;
                        break;
                    default:
                        $account->$key = $value;
                    }
            }
            $account->save();
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return false;
        }
        $this->db->where('account_id', $id)->update('stripe_accounts', array('updated_at' => time()));
        // $this->stripe_account->save($account);
        return true;
    }

    public function addSource($aid, $token)
    {
        try {
            $account = \Stripe\Account::retrieve($aid);
            $account->external_accounts->create(array("external_account" => $token));
            $account->save();
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Set A Default Payment Source
     * @todo   Convert to Stripe SDK
     * @param  string $aid Account ID
     * @param  string $sid Source ID
     * @return mixed
     */
    public function setAsDefault($aid, $sid)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$this->api_key}"));
        $url = "https://api.stripe.com/v1/accounts/{$aid}/external_accounts/{$sid}?default_for_currency=true";
        error_log($url);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_POST, 1);
        $res =  curl_exec($c);
        $response = json_decode($res);
        if(is_null($response))
        {
            $this->errors = "An unknown error occured";
            return false;
        }
        error_log($res);
        if(isset($response->error))
        {
            $this->errors = $response->error->message;
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Remove a payment source
     * @todo   Convert to Stripe SDK
     * @param  string $aid Account ID
     * @param  string $sid Source ID
     * @return mixed
     */
    public function removeSource($aid, $sid)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$this->api_key}"));
        $url = "https://api.stripe.com/v1/accounts/{$aid}/external_accounts/{$sid}";
        error_log($url);
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_CUSTOMREQUEST, "DELETE");
        $res =  curl_exec($c);
        $response = json_decode($res);
        if(is_null($response))
        {
            $this->errors = "An unknown error occured";
            return false;
        }
        error_log($res);
        if(isset($response->error))
        {
            $this->errors = $response->error->message;
            return FALSE;
        }
        return TRUE;
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

    public function updateTransferStatus($account)
    {
        $account_data = $this->db->select('*')->from('stripe_accounts')->where('account_id', $account->id)->limit(1)->get();
        if($account_data && $account_data->num_rows () == 1)
        {
            echo "1";
            $account_data = $account_data->row();
            // Check if our stripe account has been updated as enabled
            if($account_data->transfers_enabled == FALSE && $account->transfers_enabled == TRUE)
            {
                echo "2";
                // We trigger the update, and transfer all pending payouts to our newly enabled account
                $payouts = $this->db->select('*')->from('payouts')->where(array('user_id' => $account_data->user_id, 'pending' => 0))->get();
                echo "Payouts to process => ".$payouts->num_rows();
                if(!$payouts || $payouts->num_rows() == 0) return FALSE;
                echo "3";
                $payouts = $payouts->result();
                foreach($payouts as $payout)
                {
                    echo "transfering payout to account";
                    $this->load->library('stripe/stripe_transfer_library');
                    if($transfer = $this->stripe_transfer_library->create($account_data->account_id, $payout->contest_id, $payout->amount))
                    {
                        // Update that our payout has been claimed
                        $this->db->where('id', $payout->id)->update('payouts', array('account_id' => $account_data->account_id, 'transfer_id' => $transfer->id, 'pending' => 0, 'claimed' => 1));
                    } else {
                        echo $this->stripe_transfer_library->errors();
                    }
                }
            }
        }
    }

    public function errors()
    {
        return $this->errors;
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Outcrons extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function test()
    {
        echo 'test';
    }

    public function subscription()
    {
        $this->load->library(array('slack', 'subscription_lib', 'price_lib', 'stripe/stripe_charge_library', 'stripe/stripe_customer_library'));
        $this->load->model('company');

        $tomorrow = date('Y-m-d H:i:s', time() + 3600 * 24);
        $now      = date('Y-m-d H:i:s', time());
        $tmp      = $this->db->select('*')->from('user_subscription')
            ->where('end_at <=', $tomorrow)
            ->where('end_at >=', $now)
            ->limit(5)
            ->order_by('updated_at', 'ASC')->get()->result_array();
        $ids = array();

        foreach ($tmp as $data) {
            if ($data['next_level'] <= 0) {
                continue;
            }

            $amount = $this->price_lib->data['subscription'][$data['next_level']];
            if ($amount <= 0) {
                continue;
            }
            $amount = $amount * 100;

            $stripe_customer_id = $this->company->payment_details($data['user_id']);
            if (!$stripe_customer_id) {
                continue;
            }

            $customer = $this->stripe_customer_library->fetch($stripe_customer_id);
            if (!$customer) {
                continue;
            }

            $source_id = $customer->default_source;
            if (!$source_id) {
                continue;
            }

            $user_id     = $data['user_id'];
            $ids[]       = $user_id;
            $contest_id  = $msg  = '';
            $token       = null;
            $metadata    = array();
            $description = 'subscription crons user ' . $user_id . ' level ' . $data['next_level'];

            $charge = $this->stripe_charge_library->create($contest_id, $token, $stripe_customer_id, $source_id, $amount, $metadata, $description);
            if ($charge === false) {
                $msg = 'error:' . $this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : ($this->stripe_charge_library->errors() ? $this->stripe_charge_library->errors() : "An unknown error occured with payment");
            } else {
                $tmp = array('act' => 'charge_subscription', 'next_level' => $data['next_level']);
                if ($this->subscription_lib->update_level($user_id, $tmp) === true) {
                    $msg = 'success';
                } else {
                    $msg = 'error:update_level fail';
                }
            }
            $slack_msg = '[payment][subscription] ' . $description . ' ' . $msg;
            $this->slack->send($slack_msg);
            //var_dump($charge, $error);
        }
        //var_dump($this->db->last_query());
        echo count($ids) . ' done ' . implode(',', $ids);
    }
}

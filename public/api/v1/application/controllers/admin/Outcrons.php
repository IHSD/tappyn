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
            ->order_by('updated_at', 'ASC')->get()->result_array();

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

            $contest_id  = $token  = '';
            $metadata    = array();
            $description = 'Charge for contest ' . $contest_id . $post['pay_for'];

            $charge = $this->stripe_charge_library->create($contest_id, $token, $stripe_customer_id, $source_id, $amount, $metadata, $description);

        }

        var_dump($tmp, $this->db->last_query(), date_default_timezone_get());
    }
}

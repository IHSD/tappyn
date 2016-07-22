<?php defined("BASEPATH") or exit('No direct script access allowed');

class Price_lib
{
    public $data = array(
        'purchase'     => 49.99,
        'ab'           => 15,
        'launch'       => 59.99,
        'subscription' => array(
            0  => 0,
            10 => 59,
            20 => 149,
            30 => 319,
        ),
    );
    public $user_id = 0;

    public function __construct()
    {
        $this->load->library(array('vouchers_library', 'subscription_lib'));
        $this->load->model('contest');
        $this->user_id = $this->ion_auth->user()->row()->id;
    }
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get_price_from_post($post)
    {
        $result = array();
        try {
            $post['go_pay']  = isset($post['go_pay']) ? $post['go_pay'] : false;
            $post['ab_days'] = 1;
            $post['ab_aday'] = isset($post['ab_aday']) ? floatval($post['ab_aday']) : false;
            $fee             = $this->data[$post['pay_for']];
            if (!$fee) {
                throw new Exception("Missing parameters");
            }

            $voucher = false;
            if ($post['voucher_code']) {
                $msg     = '';
                $voucher = $this->vouchers_library->fetchByCode($post['voucher_code']);
                if (!$voucher) {
                    $msg = "We couldnt find a voucher with that code";

                } else if (!$this->vouchers_library->is_valid($voucher->id)) {
                    $msg = "Voucher invalid";
                }

                if ($msg) {
                    if ($post['get_price_type'] == 'check_voucher') {
                        $result['error_alert'] = $msg;
                    } else {
                        throw new Exception($msg);
                    }
                }
            }

            if ($post['pay_for'] != 'subscription' && $post['pay_for'] != 'launch') {
                $contest = $this->contest->get($post['contest_id']);
                if (!$contest) {
                    throw new Exception("We couldnt find that constest");
                }
                $status          = $this->contest->get_status($contest);
                $purchase_status = array('pending_purchase', 'pending_testing');
                if ($post['pay_for'] == 'purchase' && !in_array($status, $purchase_status)) {
                    throw new Exception("Constest status error");
                }
                //if ($post['pay_for'] == 'ab' && $status != 'pending_testing') {
                //throw new Exception("Constest status error2");
                //}

                $submission_id_count = count($post['submission_ids']);
                if ($post['pay_for'] == 'purchase' && $submission_id_count != 1) {
                    throw new Exception("purchase only one submission!");
                }
                if (!is_array($post['submission_ids']) || !$submission_id_count) {
                    throw new Exception("please check one at least");
                }
                $submissions = $this->contest->submission_ids($post['contest_id']);
                foreach ($post['submission_ids'] as $id) {
                    if (!in_array($id, $submissions)) {
                        throw new Exception("submission not exist");
                    }
                }
            }

            if ($post['pay_for'] == 'purchase') {
                $price = $submission_id_count * $fee;
                $price = 0;
            } else if ($post['pay_for'] == 'ab') {
                $free_ab = ($status == 'pending_selection') ? 0 : 15;
                if ($free_ab == 0 && !$post['ab_aday']) {
                    throw new Exception("Please enter a integer number!");
                } else if ($post['ab_aday'] <= $free_ab) {
                    $price = 0;
                } else {
                    $price = ($post['ab_aday'] * $post['ab_days']) - $free_ab;
                    //$price = $submission_id_count * $fee;
                }
            } else if ($post['pay_for'] == 'subscription') {
                $subscription = $this->subscription_lib->get_by_user_id($this->user_id);
                $diff         = isset($subscription['now_level']) ? $fee[$subscription['now_level']] : 0;
                $price        = $fee[$post['sub_level']] - $diff;
                if ($price <= 0) {
                    $price                = 0;
                    $result['no_payment'] = true;
                }
            } else {
                $price = $fee;
            }

            $result['origin_price'] = number_format($price, 2, '.', '');

            if ($voucher) {
                if ($post['go_pay'] === true && !$this->vouchers_library->redeem($voucher->id, $post['contest_id'])) {
                    $msg = $this->vouchers_library->errors() ? $this->vouchers_library->errors() : "An unknown voucher error occured";
                    throw new Exception($msg);
                }

                if ($voucher->discount_type == 'amount') {
                    $discount = $voucher->value;
                    $price    = $price - $discount;
                } else {
                    $discount = $price * $voucher_value;
                    $price    = $price - $discount;
                }
                if ($price < 000) {
                    $price = 00.00;
                }
                $result['discount'] = number_format($discount, 2, '.', '');
            }

            $result['price']   = number_format($price, 2, '.', '');
            $result['success'] = true;
        } catch (Exception $e) {
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }
        return $result;
    }
}

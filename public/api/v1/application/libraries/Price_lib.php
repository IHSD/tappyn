<?php defined("BASEPATH") or exit('No direct script access allowed');

class Price_lib
{
    public $data = array(
        'purchase' => 49.99,
        'ab'       => 0.025,
    );

    public function __construct()
    {
        $this->load->library('vouchers_library');
        $this->load->model('contest');
    }
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get_price_from_post($post)
    {
        $result = array();
        try {
            $fee = $this->data[$post['pay_for']];
            if (!$fee || ($post['pay_for'] == 'ab' && (!$post['ab_aday'] || !$post['ab_days']))) {
                throw new Exception("Missing parameters");
            }

            $voucher = false;
            if ($post['voucher_code']) {
                $voucher = $this->vouchers_library->fetchByCode($post['voucher_code']);
                if (!$voucher) {
                    throw new Exception("We couldnt find a voucher with that code");
                }
                $vid = $voucher->id;
                if (!$this->vouchers_library->is_valid($vid)) {
                    throw new Exception("Voucher invalid");
                }
            }
            $contest = $this->contest->get($post['contest_id']);
            if (!$contest) {
                throw new Exception("We couldnt find that constest");
            }
            $submission_id_count = count($post['submission_ids']);
            if (!is_array($post['submission_ids']) || !$submission_id_count) {
                throw new Exception("please check one at least");
            }
            $submissions = $this->contest->submission_ids($post['contest_id']);
            foreach ($post['submission_ids'] as $id) {
                if (!in_array($id, $submissions)) {
                    throw new Exception("submission not exist");
                }
            }

            if ($post['pay_for'] == 'purchase') {
                $price = $submission_id_count * $fee;
            } else if ($post['pay_for'] == 'ab') {
                $price = ($post['ab_aday'] * $post['ab_days']) * (1 + $fee);
            }

            if ($voucher) {
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
                $result['discount'] = number_format($discount, 2);
            }

            $result['price']   = number_format($price, 2);
            $result['success'] = true;
        } catch (Exception $e) {
            $result['successs'] = false;
            $result['message']  = $e->getMessage();
        }
        return $result;
    }
}

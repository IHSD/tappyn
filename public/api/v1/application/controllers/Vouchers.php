<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vouchers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("Unauthorized Access")->code(401)->respond();
            exit();
        }
        $this->load->library('vouchers_library');
    }

    /**
     * Endpoint to validate a voucher on behalf of the user
     * @param  integer  $vid Voucher ID
     * @return void
     */
    public function is_valid()
    {
        $code = $this->input->post('voucher_code');
        $voucher = $this->vouchers_library->fetchByCode($code);
        if(!$voucher)
        {
            $this->responder->fail("We couldnt find a voucher with that code")->code(500)->respond();
            return;
        }
        $vid = $voucher->id;
        if($this->vouchers_library->is_valid($vid))
        {
            $price = 49.99;

            if($voucher->discount_type == 'amount')
            {
                $discount = $voucher->value;
                $price = $price - $discount;
            } else {
                $discount = $price * $voucher_value;
                $price = $price - $discount;
            }
            if($price < 000) $price = 00.00;
            $this->responder->data(array('is_valid' => true, 'price' => number_format($price, 2), 'discount' => number_format($discount, 2)))->respond();
        } else {
            $this->responder->fail(($this->vouchers_library->errors() ? $this->vouchers_library->errors() : "Voucher invalid"))->code(500)->respond();
        }
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Voucher_model_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('voucher');
        $this->obj = $this->CI->voucher;
    }

    public function test_count()
    {
        $contest_id = 1;
        $count = $this->obj->count();
        $this->assertInternalType("integer", $count);
    }

    public function test_redeem()
    {
        $redeem = $this->obj->redeem(1, 1);
        $this->assertTrue($redeem);
    }
}

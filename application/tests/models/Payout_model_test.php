<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payout_model_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('payout');
        $this->obj = $this->CI->payout;
        $this->faker = Faker\Factory::create();
    }

    // public function test_update()
    // {
    //     $update = $this->obj->update(1, array();
    //     $this->assertTrue($update);
    // }
    //
    // public function test_create()
    // {
    //
    // }
}

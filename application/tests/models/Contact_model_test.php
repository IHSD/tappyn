<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contact_model_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('contact');
        $this->obj = $this->CI->contact;
        $this->faker = Faker\Factory::create();
    }

    public function test_create()
    {
        $create = $this->obj->create('member', $this->faker->email, $this->faker->text(200));
        $this->assertTrue($create);
    }

    public function test_mailing_list()
    {
        $create = $this->obj->addToMailing($this->faker->email);
        $this->assertTrue($create);
    }

    public function test_failed_mailing_list()
    {
        $create = $this->obj->addToMailing(NULL);
        $this->assertFalse($create);
    }

    public function test_errors()
    {
        $this->assertFalse($this->obj->errors());
    }
}

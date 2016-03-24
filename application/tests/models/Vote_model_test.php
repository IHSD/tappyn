<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vote_model_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('vote_mode');
        $this->obj = $this->CI->vote_model;
        $this->faker = Faker\Factory::create();
    }

    public function create()
    {
        $create = $this->obj->create(1,1,1);
        $this->assertTrue($create);
    }
}

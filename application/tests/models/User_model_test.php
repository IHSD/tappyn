<?php defined("BASEPATH") or exit('No direct script access allowed');

class User_model_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->model('user');
        $this->obj = $this->CI->user;
    }

    public function test_count()
    {
        $count = $this->obj->count(array(), array());
        $this->assertInternalType("int", $count);
    }

    public function test_fetch_single()
    {
        $users = $this->obj->fetch()->row();
        $this->id = $users->id;
        $this->assertInternalType("object", $users);
    }

    public function test_fetch_results()
    {
        $users = $this->obj->fetch()->result();
        $this->assertInternalType("array", $users);
    }

    public function test_profile()
    {
        $profile = $this->obj->profile(1);
        $this->assertInternalType("object", $profile);
    }

    public function test_save_profile()
    {
      
    }
}

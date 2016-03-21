<?php

class Contest_model_test extends TestCase
{
  public function setUp()
  {
      $this->resetInstance();
      $this->CI->load->model('contest');
      $this->obj = $this->CI->contest;
  }

  public function test_count()
  {
      $count = $this->obj->count(array(), array());
      $this->assertInternalType("int", $count);
  }

  public function test_fetch_single()
  {
      $users = $this->obj->fetch()->row();
      $this->assertInternalType("object", $users);
  }

  public function test_fetch_results()
  {
      $users = $this->obj->fetch()->result();
      $this->assertInternalType("array", $users);
  }
}

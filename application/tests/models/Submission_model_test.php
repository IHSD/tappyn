<?php

class Submission_model_test extends TestCase
{
  public function setUp()
  {
    $this->resetInstance();
    $this->CI->load->model('submission');
    $this->obj = $this->CI->submission;
  }

  public function test_count()
  {
    $contest_id = 1;
    $count = $this->obj->count(array('contest_id' => $contest_id));
    $this->assertInternalType("integer", $count);
  }

  public function test_parent()
  {
    $parent = $this->obj->parentContest(1);
    $this->assertInternalType("object", $parent);
  }

  public function test_get_active()
  {
      $submissions = $this->obj->getActive(1);
      $this->assertInternalType("array", $submissions);
  }

  public function test_query()
  {
      $results = $this->obj->query("SELECT * FROM submissions", array());
      $this->assertInternalType("array", $results->result());
  }
}

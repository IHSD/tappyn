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

  public function test_validate_ages_as_0()
  {
      $data = array('age' => 0);
      $data = $this->obj->validate($data);
      $test = ($data['min_age'] == 18 && $data['max_age'] == 45);
      $this->assertTrue($test);
  }

  public function test_validate_ages_as_1()
  {
      $data = array('age' => 1);
      $data = $this->obj->validate($data);
      $test = ($data['min_age'] == 18 && $data['max_age'] == 24);
      $this->assertTrue($test);
  }

  public function test_validate_ages_as_2()
  {
      $data = array('age' => 2);
      $data = $this->obj->validate($data);
      $test = ($data['min_age'] == 25 && $data['max_age'] == 34);
      $this->assertTrue($test);
  }

  public function test_validate_ages_as_3()
  {
      $data = array('age' => 3);
      $data = $this->obj->validate($data);
      $test = ($data['min_age'] == 35 && $data['max_age'] == 45);
      $this->assertTrue($test);
  }

  public function test_validate_ages_as_4()
  {
      $data = array('age' => 4);
      $data = $this->obj->validate($data);
      $test = ($data['min_age'] == 45 && $data['max_age'] == 45);
      $this->assertTrue($test);
  }

  public function test_age_not_required()
  {
      $age = 35;
      $success = $this->obj->userInAgeRange(18,45,$age);
      $this->assertTrue($success);
  }

  public function test_user_age_not_eligible()
  {
      $age = 35;
      $success = $this->obj->userInAgeRange(18,24,$age);
      $this->assertFalse($success);
  }

  public function test_user_age_eligible()
  {
      $age = 35;
      $success = $this->obj->userInAgeRange(18,45,$age);
      $this->assertTrue($success);
  }

  public function test_user_over_45()
  {
      $age = 75;
      $success = $this->obj->userInAgeRange(18,45,$age);
      $this->assertTrue($success);
  }

  public function test_gender_not_required()
  {
      $gender = 1;
      $success = $this->obj->userIsGender(0,$gender);
      $this->assertTrue($success);
  }

  public function test_user_gender_not_eligible()
  {
      $gender = 1;
      $success = $this->obj->userIsGender(2,$gender);
      $this->assertFalse($success);
  }

  public function test_user_gender_eligible()
  {
      $gender = 1;
      $success = $this->obj->userIsGender(1,$gender);
      $this->assertTrue($success);
  }
}

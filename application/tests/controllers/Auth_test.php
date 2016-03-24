<?php

class Auth_test extends TestCase
{
    public function setUp()
    {
        $this->resetInstance();
        $this->CI->load->library('ion_auth');
        $this->obj = $this->CI->ion_auth;
    }

    public function test_login()
    {
      $user = $this->obj->login('rob@ihsdigital.com', 'davol350', FALSE);
      $this->assertEquals($user, 1);
    }

    public function test_session()
    {
      $user = $this->obj->login('rob@ihsdigital.com', 'davol350', FALSE);
      $session = $this->CI->load->library('session');
      $session = $this->CI->session;
      $this->assertInternalType("array", $session->userdata());
    }
}

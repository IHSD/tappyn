<?php

class Test extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('mailer');
    }

    public function index()
    {
        $this->data = array(
            'password' => bin2hex(openssl_random_pseudo_bytes(5)),
            'email' => 'rob@ihsdigital.com'
        );
        // $this->mailer
        //     ->to('rob@ihsdigital.com')
        //     ->from('Registration@tappyn.com')
        //     ->subject('Account successfully created')
        //     ->html($this->load->view('emails/submission_chosen', $this->data, TRUE))
        //     ->send();
        $this->load->view('emails/submission_chosen', $this->data);
    }
}

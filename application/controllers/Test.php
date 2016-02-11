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
            'headline' => "This is an awesome headline",
            'text' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
            'contest' => "Super awesome contest",
            'company' => "Nike"
        );
        $this->mailer
            ->to('rob@ihsdigital.com')
            ->from('squad@tappyn.com')
            ->subject('Your submission has successfully been created')
            ->html($this->load->view('emails/submission_success', $this->data, TRUE))
            ->send();
        //$this->load->view('emails/submission_success', $this->data);
    }
}

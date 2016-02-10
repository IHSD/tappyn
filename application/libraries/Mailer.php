<?php defined("BASEPATH") or exit("No direct script access allowed");

class Mailer
{
    protected $api_key;

    public function __construct()
    {
        $this->config->load('secrets');
        $api_key = $this->config->item('sendgrid_api_key');
        $this->handler = new SendGrid($api_key);
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function test()
    {
        $email = new SendGrid\Email();
        $email
            ->addTo('rob@ihsdigital.com')
            ->setFrom('squad@tappyn.com')
            ->setSubject('Test email from tappyn.com')
            ->setText('HelloWorld');
        $this->handler->send($email);
    }
}

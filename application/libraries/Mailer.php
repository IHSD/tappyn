<?php defined("BASEPATH") or exit("No direct script access allowed");

class Mailer
{
    protected $api_key;
    protected $to;
    protected $from;
    protected $subject;
    protected $html;
    protected $callback;
    protected $eid;

    public function __construct()
    {
        $this->config->load('secrets');
        $api_key = $this->config->item('sendgrid_api_key');
        $this->handler = new SendGrid($api_key);
        $this->email = new SendGrid\Email();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function event($event)
    {
        
    }

    public function to($to)
    {
        $this->to = $to;
        return $this;
    }

    public function from($from)
    {
        $this->from = $from;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function html($html)
    {
        $this->html = $html;
        return $this;
    }

    public function id($to, $event)
    {
        $this->db->insert('emails', array('to' =>$to, 'event' => $event, 'created' => time()));
        return $this->db->insert_id();
    }

    public function send()
    {
        $this->email
            ->addTo($this->to)
            ->setFrom($this->from)
            ->setSubject($this->subject)
            ->setHtml($this->html);
        $this->handler->send($this->email);
        return TRUE;
    }
}

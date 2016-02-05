<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contact extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($customer, $email, $subject, $topic, $message)
    {
        return $this->db->insert('contacts', array(
            'customer' => $customer,
            'email' => $email,
            'subject' => $subject,
            'topic' => $topic,
            'message' => $message
        ));
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contact extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function create($customer, $email, $message)
    {
        return $this->db->insert('contacts', array(
            'customer' => $customer,
            'email' => $email,
            'message' => $message
        ));
    }

    public function errors()
    {
        return false;
    }
}

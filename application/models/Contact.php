<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contact extends MY_Model
{
    protected $table = 'contacts';
    protected $order_by;
    protected $order_dir;
    protected $errors = FALSE;
    public function __construct()
    {
        parent::__construct();
        $this->order_by = 'contacts.id';
        $this->order_dir = 'desc';
    }

    public function create($customer, $email, $message)
    {
        return $this->db->insert('contacts', array(
            'customer' => $customer,
            'email' => $email,
            'message' => $message
        ));
    }

    public function addToMailing($email)
    {
        if($this->db->insert('mailing_list', array(
            'email' => $email
        )))
        {
            return true;
        } else {
            $this->errors = "An unexpected error occured :: {$this->db->error()['code']}";
            error_log($this->db->error()['message']);
            return FALSE;
        }
    }

    public function errors()
    {
        return $this->errors;
    }

    public function count($where = array(), $like = array())
    {
        $this->db->select("COUNT(*) as count")->from('contacts');
        if(!empty($where)) $this->db->where($where);
        if(!empty($like)) $this->db->like($like);

        return (int) $this->db->get()->row()->count;
    }
}

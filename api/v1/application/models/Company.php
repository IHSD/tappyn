<?php defined("BASEPATH") or exit('No direct script access allowed');

class Company extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get($id)
    {
        $company = $this->db->select('*')->from('profiles')->where('id', $id)->limit(1)->get();
        if(!$company || $company->num_rows() == 0)
        {
            return FALSE;
        }
        return $company->row();
    }

    public function payment_details($uid)
    {
        $info = $this->db->select('*')->from('stripe_customers')->where('user_id', $uid)->limit(1)->get();
        if($info && $info->num_rows() == 1)
        {
            return $info->row()->customer_id;
        }
        return false;
    }
}

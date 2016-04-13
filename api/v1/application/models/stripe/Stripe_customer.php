<?php  defined("BASEPATH") or exit('No direct script access allowed');

class Stripe_customer extends CI_Model
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

    public function save($uid, $customer)
    {
        $check = $this->db->select('*')->from('stripe_customers')->where(array('user_id' => $uid, 'customer_id' => $customer->id))->limit(1)->get();
        if($check && $check->num_rows() > 0)
        {
            return $this->db->where('id', $check->row()->id)->update('stripe_customers', array(
                'updated_at' => time(),
                'currency' => $customer->currency,
                'default_source' => $customer->default_source
            ));
        } else {
            return $this->insert($uid, $customer);
        }
    }
    public function insert($uid, $customer)
    {
        return $this->db->insert('stripe_customers', array(
            'customer_id' => $customer->id,
            'created' => $customer->created,
            'currency' => $customer->currency,
            'default_source' => $customer->default_source,
            'email' => $customer->email,
            'user_id' => $uid
        ));
    }
}

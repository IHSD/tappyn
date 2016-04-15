<?php defined("BASEPATH") or exit('No direct script access allowed');

class Customer extends MY_Model
{
    private static $db;
    private static $table = 'stripe_customer_id';

    function __construct($data = NULL)
    {
        parent::__construct();
        self::$db = &get_instance()->db;
    }

    static function get($params)
    {
        $check = self::$db->select('*')->from(self::$table)->where($params)->limit(1)->get();
        if($check && $check->num_rows() == 1)
        {
            return $check->row(0, 'Customer');
        }
        return FALSE:
    }

    static function getFromStripe()
    {
        try{
            $customer = \Stripe\Customer::retrieve($id);
        } catch(Exception $e) {
            $this->errors = $e->getMessage();
            return FALSE;
        }
        return new Customer($customer);
    }

    protected function create()
    {

    }

    protected function saveToDb()
    {

    }

    protected function update()
    {

    }

    protected function add_payment_method()
    {

    }

    protected function remove_payment_method()
    {

    }

    protected function set_method_as_default()
    {

    }

    protected function delete()
    {

    }
}

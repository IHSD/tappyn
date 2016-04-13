<?php defined("BASEPATH") or exit("No direct script access allowed");

class MY_Model extends CI_Model
{
    private static $table = NULL;
    protected $data = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function __get($var)
    {
        if(isset($this->data[$var])) return $this->data[$var];
        throw new Exception("Undefined property: ".get_called_class()."::{$var}");
    }

    public function table()
    {
        return self::$table;
    }

    public function setData($data)
    {
        foreach($data as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }

    public function data()
    {
        return $this->data;
    }
}

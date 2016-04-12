<?php defined("BASEPATH") or exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    protected $_fields = array();
    public function __construct()
    {
        parent::__construct();
    }

    public function model($model, $name = '', $db_conn = false)
    {
        if(is_array($model))
        {
            foreach($model as $mod)
            {
                $this->model($mod);
            }
        } else {
            parent::model($model);
            foreach(glob(APPPATH.'models/fields/'.ucfirst($model).'*.php') as $file)
            {
                $filename = str_replace('.php', '', $file);
                if(in_array($file, $this->_fields))
                {
                    return;
                }
                $this->_fields[] = $filename;
                require_once($file);
            }
        }
    }
}

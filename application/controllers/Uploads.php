<?php defined("BASEPATH") or exit('No direct script access allowed');

class Uploads extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($filename = NULL)
    {
        if(is_null($filename)) return;
        $name = APPPATH.'uploads/'.$filename;
        if(!file_exists($name)) return;
        $fp = fopen($name, 'rb');

        header("Content-Type: image/png");
        header("Content-Length: " . filesize($name));

        fpassthru($fp);
        exit;
    }
}

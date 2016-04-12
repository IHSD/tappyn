<?php defined("BASEPATH") or exit('No direct script access allowed');

class Response
{
    protected $success = TRUE;
    protected $data = array();
    protected $code = 200;
    protected $message = FALSE;
    protected $format = 'json';

    public function __construct()
    {

    }

    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    public function code($code)
    {
        $this->code = $code;
        return $this;
    }

    public function fail($error)
    {
        $this->success = FALSE;
        $this->error = $error;
        return $this;
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function respond()
    {
        $results = array();
        if($this->success)
        {
            $results['data'] = $this->data;
        }
        else
        {
            $results['error'] = $this->error;
        }
        $results['success'] = $this->success;
        $results['code'] = $this->code;
        echo json_encode($results);
    }
}

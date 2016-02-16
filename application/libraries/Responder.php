<?php defined("BASEPATH") or exit('No direct script access allowed');

class Responder
{
    protected $success = TRUE;
    protected $data = array();
    protected $error = 'An unknown error occured';
    protected $message = FALSE;
    protected $status_code = 200;

    public function __construct()
    {

    }

    public function code($code)
    {
        $this->status_code = $code;
        return $this;
    }

    public function fail($error)
    {
        $this->success = false;
        $this->error = $error;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function data($data)
    {
        $this->data = $data;
        return $this;
    }

    public function respond()
    {
        $results = array(
            'http_status_code'  => $this->status_code,
            'success'           => $this->success
        );
        if($this->success){
            $results['data'] = $this->data;
            $results['message'] = $this->message;
        } else {
            $results['error'] = $this->error;
        }
        echo json_encode($results);
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Request
{
    protected $headers;
    protected $body;
    protected $id;
    protected $method;

    public function __construct()
    {
        $this->headers = getallheaders();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->body = file_get_contents('php://input');
    }

    public function token()
    {
        return $this->headers('X-Tappyn-Access-Token');
    }

    public function headers($key = NULL)
    {
        if(is_null($key))
        {
            return $this->headers;
        }
        else
        {
            if(isset($this->headers[$key]))
            {
                return $this->headers[$key];
            }
        }
        return FALSE;
    }

    public function method()
    {
        return $this->method;
    }
}

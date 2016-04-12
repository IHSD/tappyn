<?php defined("BASEPATH") or exit('No direct script access allowed');

use \Firebase\JWT\JWT;

class Token
{
    protected $payload;
    protected $header;
    protected $signature;
    protected $token;
    protected $secret_key = NULL;
    protected $error = NULL;

    public function __construct()
    {
        $this->config->load('secrets', TRUE);
        $this->secret_key = $this->config->item('json_secret_key', 'secrets');
        if(is_null($this->secret_key))
        {
            throw new Exception("Using JSON Tokens without a secret key.  Please set the 'json_secret_key' in application/config/secrets.php");
        }
    }

    public function __get($var)
    {
        if($var == 'config' || $var == 'headers')
        {
            return get_instance()->$var;
        }
        throw new Exception("Undefined property Token->{$var}");
    }

    public function __call($method, $args = array())
    {
        if(property_exists($this->payload, $method))
        {
            return $this->payload->{$method};
        }
        throw new Exception("Call to undefined function Token::{$method}()");
    }

    public function setToken($token)
    {
        $this->token = $token;
        if($decoded = $this->decode())
        {
            $this->payload = $decoded;
        }
        return TRUE;
    }

    public function unsetToken()
    {
        $this->token = NULL;
    }

    public function encode($data)
    {
        return JWT::encode($data, $this->secret_key);
    }

    public function payload()
    {
        return is_null($this->payload) ? FALSE : $this->payload;
    }

    /**
     * Decode a token and return the payload
     * @return object|boolean
     */
    public function decode()
    {
        try {
            $decoded = JWT::decode($this->token, $this->secret_key, array('HS256'));
        } catch(Exception $e) {
            $this->error = $e->getMessage();
            return FALSE;
        }
        return $decoded;
    }

    public function errors()
    {
        return $this->error;
    }
}

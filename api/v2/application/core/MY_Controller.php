<?php defined("BASEPATH") or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    protected $user = FALSE;
    public $request;
    public $response;

    public function __construct()
    {
        parent::__construct();

        // Load some of our libraries
        $this->load->library(array(
            'request',
            'token',
            'response'
        ));

        $this->load->model(array(
            'notification'
        ));
        // Set and decode our token
        $this->token->setToken($this->request->token());

        // If a payload existed, we set our user object
        if($this->token->payload())
        {
            try {
                $this->user = $this->token->user();
            } catch(Exception $e) {
                error_log("Payload existed, but the token did not contain user data");
                $this->token->unsetToken();
            }
        }
        // Check authentication for our route
        $this->config->load('authorization');
        $this->config->load('tappyn_hooks', TRUE);
        if(!$this->is_authorized(get_called_class(), $this->router->fetch_method()))
        {
            // Redirect them to a 403 unauthorized page;
            redirect('errors/show_403', 'refresh');
        }

        /* Load and instantiate our Hook manager */
        include_once(APPPATH.'libraries/Hook.php');
        Hook::initialize($this->config->item('hooks', 'tappyn_hooks'));
        Hook::register_model($this->notification);
    }

    protected function is_authorized($class, $method)
    {
        $auth_routes = $this->config->item(strtolower(get_called_class()));
        if(is_null($auth_routes))
        {
            throw new Exception("Routes for controller {$class} have not been declared yet");
        }
        if($auth_routes === FALSE) return TRUE;
        if($auth_routes === TRUE || in_array(strtolower($method), $auth_routes))
        {
            return ($this->user !== FALSE);
        }
        return TRUE;
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $user = NULL;
    public $is_logged_in = FALSE;

    public function __construct()
    {
        parent::__construct();
        if($this->ion_auth->logged_in())
        {
            $this->is_logged_in = TRUE;
            $this->user = $this->ion_auth->user()->row();
        }

        include_once(APPPATH.'libraries/Hook.php');
        Hook::initialize();
        $this->load->library('test_library');
        Hook::register_library($this->test_library);
        $this->load->config('tappyn_hooks', TRUE);
        // Load hooks for the currently called controller
        $class = strtolower(!is_null($this->router->directory) ? $this->router->directory.'_'.get_called_class() : get_called_class());
        $hooks = $this->config->item('hooks', 'tappyn_hooks');
        if(isset($hooks[$class]))
        {
            foreach($hooks[$class] as $key => $hook)
            {
                // Make sure the library is loaded and registered
                if(!$this->load->is_loaded($hook['class'])) $this->load->library($hook['class']);
                Hook::register_library($this->{$hook['class']});

                // Finally, register the hooks
                Hook::register(
                    $hook['event'],
                    $hook['name'],
                    $hook['class'],
                    $hook['method'],
                    isset($hook['args']) ? $hook['args'] : array()
                );
            }
        }
        Hook::trigger('hook_initialize');
    }
}

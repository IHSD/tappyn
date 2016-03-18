<?php defined("BASEPATH") or exit('No direct script access allowed');

class Feed extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || $this->ion_auth->in_group(3))
        {
            $this->responder->fail("Only logged in users can view their Content Feed")->code(403)->respond();
            exit();
        }
        $this->load->library('feed_library');
    }

    /**
     * Gather a users social content feed
     * @return void
     */
    public function index()
    {

    }

    public function fetch()
    {
        
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Followers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Check that they are logged in
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in to access this area")->code(403)->respond();
            exit();
        }

        // Check that their account is active
        if($this->ion_auth->user()->row()->active == 0)
        {
            $this->responder->fail("You cant follow any companies until you verify your email")->code(500)->respond();
            exit();
        }
        $this->load->library('follows');
    }

    /**
     * Fetch everybody I follow
     * @return void
     */
    public function index()
    {
        $following = $this->follows->following($this->ion_auth->user()->row()->id);
        $this->responder->data(array('following' => $following))->respond();
    }

    /**
     * Fetch everybody that follows me
     * @return void
     */
    public function following()
    {
        $followers = $this->follows->followers($this->ion_auth->user()->row()->id);
        $this->responder->data(array('followers' => $followers))->respond();
    }

    public function create()
    {
        $fid = $this->input->post('follower_id');
        if($this->follows->follow($this->ion_auth->user()->row()->id, $fid))
        {
            $this->responder->data(array())->message("User successfully followed")->respond();
            return;
        }
        else
        {
            $this->responder->fail($this->follows->errors() ? $this->follows->errors() : "An unkown error occured")->code(500)->respond();
            return false;
        }
    }

    public function count()
    {
        $following = $this->follows->countFollowing($this->ion_auth->user()->row()->id);
        $followers = $this->follows->countFollowers($this->ion_auth->user()->row()->id);
        $this->responder->data(array(
            'followers' => $followers,
            'following' => $following
        ))->respond();
    }
}

<?php defined("BASEPATH") or exit('No direct script acccess allowed');

class Interests extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(2))
        {
            $this->responder->fail("Unaithorized access")->code(403)->respond();
            exit();
        }
        $this->load->library('interest');
        $this->interest->setUser($this->ion_auth->user()->row()->id);
        $this->interest->setDatabase($this->db);
    }

    /**
     * Fetch all interests, as well as wether or not the user likes them
     * @return void
     */
    public function index()
    {
        $this->interest->setUser($this->ion_auth->user()->row()->id);
        $interests = $this->interest->tree();
        $this->responder->data(array(
            'interests' => $interests
        ))->respond();
    }

    /**
     * Mark an interest as followed by the user
     * @param void $id ID of the interest
     */
    public function add($id = NULL)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || is_null($id))
        {
            $this->responder->fail("Invalid Request")->code(500)->respond();
            return;
        }
        if($this->interest->addToUser($id))
        {
            $this->responder->respond();
        } else {
            $this->responder->fail(($this->interest->errors() ? $this->interest->errors() : "An unkonwn error occured"))->code(500)->respond();
        }
    }

    /**
     * Mark an interest as no longer followed
     * @param  integer $id ID of the interest
     * @return void
     */
    public function remove($id = NULL)
    {
        if($_SERVER['REQUEST_METHOD'] !== 'POST' || is_null($id))
        {
            $this->responder->fail("Invalid Request")->code(500)->respond();
            return;
        }
        if($this->interest->removeFromUser($id))
        {
            $this->responder->respond();
        } else {
            $this->responder->fail(($this->interest->errors() ? $this->interest->errors() : "An unkonwn error occured"))->code(500)->respond();
        }
    }


}

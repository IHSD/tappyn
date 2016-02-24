<?php define("BASEPATH") or exit('No irect script access allowed');

class Votes extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->error(array(
                'error' => "You must be logged in to perform this function"
            ))->code(401)->respond();
            // We have to exit so that we don't continue request execution
            exit();
        }
    }

    public function index()
    {

    }

    public function show()
    {

    }

    /**
     * Create a new vote
     * @return void
     */
    public function create()
    {
        if(!$this->input->post('submission_id'))
        {
            $this->responder->error("What submission did you want to vote for?")->code(500)->respond();
            return;
        }
        $submission_id = $this->input->post('submission_id');
        // Check if th user has voted for this submission
        if($this->vote->upvote($submission_id, $this->ion_auth->user()->row()->id))
        {
            $this->responder->message(
                "Upvote successful"
            )->respond();
        }
        else
        {
            $this->responder->fail(
                ($this->vote->errors() ? $this->vote_errors() : 'An unknown error occured')
            )->code(500)->respond();
        }
    }
}

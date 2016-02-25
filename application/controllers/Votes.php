<?php defined("BASEPATH") or exit('No irect script access allowed');

class Votes extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in to perform this function")->code(401)->respond();
            // We have to exit so that we don't continue request execution
            exit();
        }
        $this->load->library('vote');
        $this->load->model('contest');
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
        if(!$this->input->post('submission_id') ||
            !$this->input->post('contest_id'))
        {
            $this->responder->fail("What submission did you want to vote for?")->code(500)->respond();
            return;
        }
        $submission_id = $this->input->post('submission_id');
        $submission = $this->submission->get($submission_id);
        if($submission->owner == $this->ion_auth->user()->row()->id)
        {
            $this->responder->fail("You cant vote for your own submission")->code(500)->respond();
            return;
        }
        $contest = $this->contest->get($submission->contest_id);
        if(!$contest) $this->responder->fail()->code(500)->respond();
        // Check if th user has voted for this submission
        if($this->vote->upvote($submission_id, $contest->id, $this->ion_auth->user()->row()->id))
        {
            $this->responder->message(
                "Upvote successful"
            )->respond();
            $this->user->attribute_points($this->ion_auth->user()->row()->id, $this->config->item('points_per_upvote'));
        }
        else
        {
            $this->responder->fail(
                ($this->vote->errors() ? $this->vote->errors() : 'An unknown error occured')
            )->code(500)->respond();
        }
    }
}

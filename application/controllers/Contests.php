<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends CI_Controller
{
    protected $params = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('contest');
        $this->load->model('submission');
        $this->load->library('submission_library');
        $this->load->library('mailer');
    }

    /**
     * View all available contests
     * @return void
     */
    public function index()
    {
        $this->params = array(
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s')
        );
        $config['base_url'] = base_url().'contests/index';
        $config['total_rows'] = $this->contest->count($this->params);
        $config['per_page'] = 20;
        $this->pagination->initialize($config);
        $limit = $config['per_page'];
        $offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $contests = $this->contest->fetchAll($this->params, 'start_time', 'desc', $limit, $offset);
        if($contests !== FALSE)
        {
            $this->responder->data($contests)->respond();
        } else {
            $this->responder->fail("An unknown error occured")->code(400)->respond();
        }
    }

    /**
     * Fetch a single contest
     * @param  integer $id
     * @return void
     */
    public function show($cid)
    {
        $contest = $this->contest->get($cid);

        if(!$contest)
        {
            $this->responder->fail(
                "That contest does not exist"
            )->code(404)->respond();
        } else {
            $this->responder->data(array(
                'contest' => $contest
            ))->respond();
        }
    }
    /**
     * Create a new contest, or render the creation form
     * @return void
     */
    public function create()
    {
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(3))
        {
            $this->responder->code(403)->respond();
            return;
        }

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('audience_description', 'Audience Description', 'required');
        $this->form_validation->set_rules('how_your_different', 'How Your Different', 'required');
        $this->form_validation->set_rules('objective', 'Objective', 'required');
        $this->form_validation->set_rules('platform', 'Format', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');
        $this->form_validation->set_rules('age_range', 'Age Range', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');

        if($this->form_validation->run() == true)
        {
            // Do some preliminary formatting
            $data = array(
                'title' => $this->input->post('title'),
                'audience' => $this->input->post('audience_description'),
                'different' => $this->input->post('how_your_different'),
                'objective' => $this->input->post('objective'),
                'platform' => $this->input->post('platform'),
                'location' => $this->input->post('location'),
                'age' => $this->input->post('age_range'),
                'gender' => $this->input->post('gender'),
                'owner' => $this->ion_auth->user()->row()->id,
                'stop_time' => date('Y-m-d H:i:s', strtotime('+7 days'))
            );
        }
        if($this->form_validation->run() == true && ($cid = $this->contest->create($data)))
        {
            $this->responder->message($this->contest->messages())->data(array('id' => $cid))->respond();
        }
        else
        {
            $this->responder->fail(
                (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : 'An unknown error occured'))
            )->code(400)->respond();
        }
    }

    /**
     * Set a submission as the winner of the contest
     * @param  integer $cid Contest ID
     * @param  integer $sid Submission ID
     * @return void
     */
    public function select_winner($cid)
    {
        $this->load->library('payout');
        $this->load->model('user');
        $sid = $this->input->post('submission');

        // Check that submission exists
        if(!$submission = $this->submission->get($sid))
        {
            $this->session->set_flashdata('error', 'That submission does not exist');
            redirect("contests/show/{$cid}", "refresh");
        }
        // Check that contest exists
        else if(!$contest = $this->contest->get($cid))
        {
            $this->session->set_flashdata('error', "We couldn't find contest with id {$cid}");
            redirect("contests/index", 'refresh');
        }
        // Check that the contest has ended
        else if($contest->stop_time > date('Y-m-d H:i:s'))
        {
            $this->session->set_flashdata('error', "The contest must be over in order to select a winner");
            redirect("contests/show/{$cid}", 'refresh');
        }
        // Check that we are admin or the ccontest owner
        if(!$this->ion_auth->user()->row()->id !== $contest->owner)
        {
            if(!$this->ion_auth->is_admin())
            {
                $this->session->set_flashdata('error', "You must own the contest to select a winner");
                redirect("contests/show/{$cid}", 'refresh');
            }
        }
        $payout = $this->payout->exists(array('contest_id' => $cid));
        if($payout)
        {
            $this->session->set_flashdata('error', "A submission has already been chosen as the winner");
            redirect("contests/show/{$cid}", 'refresh');
        }
        // Attempt to create the payouts
        if($pid = $this->payout->create($cid, $sid))
        {
            // Send the email congratulating the user
            error_log("Sending email to ".$this->ion_auth->user($submission->owner)->row()->email);
            $this->mailer
                ->to($this->ion_auth->user($submission->owner)->row()->email)
                ->from("squad@tappyn.com")
                ->subject("Congratulations, you're submission won!")
                ->html($this->load->view('emails/submission_chosen', array(), TRUE))
                ->send();

            // Tell the contest they have successfully selected a winner!
            $this->session->set_flashdata('message', "Submission {$sid} has been chosen as a winner");
            redirect("contests/show/{$cid}", "refresh");
        }
        // Something happened, so lets just route tham back to the contest page with an error
        else
        {
            $this->session->set_flashdata('error', ($this->payout->errors() ? $this->payout_errors() : "An unknown error occured"));
            redirect("contests/show/{$cid}", 'refresh');
        }
    }
}

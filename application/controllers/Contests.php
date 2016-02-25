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
     *
     * Also, we log the impression so we can track views
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
            $contest->views = $this->contest->views($cid);
            $this->responder->data(array(
                'contest' => $contest
            ))->respond();
            $this->contest->log_impression($cid);
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
            $this->responder->fail("You need to be logged in as a company to create contests")->code(403)->respond();
            return;
        }

        $this->form_validation->set_rules('audience_description', 'Audience Description', 'required');
        $this->form_validation->set_rules('how_your_different', 'How Your Different', 'required');
        $this->form_validation->set_rules('objective', 'Objective', 'required');
        $this->form_validation->set_rules('platform', 'Format', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');
        $this->form_validation->set_rules('summary', 'Summary', 'required');

        if($this->form_validation->run() == true)
        {
            $start_time = ($this->input->post('start_time') ? $this->input->post('start_time') : date('Y-m-d H:i:s'));
            // Do some preliminary formatting
            $data = array(
                'audience'          => $this->input->post('audience_description'),
                'short_description' => $this->input->post('short_description'),
                'different'         => $this->input->post('how_your_different'),
                'objective'         => $this->input->post('objective'),
                'platform'          => $this->input->post('platform'),
                'location'          => $this->input->post('location'),
                'age'               => $this->input->post('age_range'),
                'gender'            => $this->input->post('gender'),
                'owner'             => $this->ion_auth->user()->row()->id,
                'start_time'        => $start_time,
                'stop_time'         => date('Y-m-d H:i:s', strtotime('+7 days'))
            );
            $images = array();
            if($this->input->post('additional_image_1')); $images[] = $this->input->post('additional_image_1');
            if($this->input->post('additional_image_2')); $images[] = $this->input->post('additional_image_2');
            if($this->input->post('additional_image_3')); $images[] = $this->input->post('additional_image_3');
            if(!empty($images)) $data['additional_images'] = json_encode($images);
        }
        if($this->form_validation->run() == true && ($cid = $this->contest->create($data)))
        {
            $this->responder->message($this->contest->messages())->data(array('id' => $cid))->respond();
        }
        else
        {
            $this->responder->fail(
                (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : 'An unknown error occured'))
            )->code(500)->respond();
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

        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in to perform this action")->code(401)->respond();
            return;
        }
        // Check that submission exists
        if(!$submission = $this->submission->get($sid))
        {
            $this->responder->fail("That submission does not exist")->code(500)->respond();
            return;
        }
        // Check that contest exists
        else if(!$contest = $this->contest->get($cid))
        {
            $this->responder->fail("We couldn't find contest with id {$cid}")->code(500)->respond();
            return;
        }
        // Check that the contest has ended
        else if($contest->stop_time > date('Y-m-d H:i:s'))
        {
            $this->responder->fail("This contest has not finished yet")->code(500)->respond();
            return;
        }
        $company_name = $this->db->select('name')->from('profiles')->where("id", $contest->owner)->get();
        if($company_name)
        {
            $company_name = $company_name->row()->name;
        } else {
            $company_name = '';
        }
        // Check that we are admin or the ccontest owner
        if(!$this->ion_auth->user()->row()->id !== $contest->owner)
        {
            if(!$this->ion_auth->is_admin())
            {
                $this->responder->fail('You must own the contest to select a winner')->code(403)->respond();
                return;
            }
        }
        $payout = $this->payout->exists(array('contest_id' => $cid));
        if($payout)
        {
            $this->responder->fail("A submission has already been chosen as the winner")->code(500)->respond();
            return;
        }
        // Attempt to create the payouts
        if($pid = $this->payout->create($cid, $sid))
        {
            // Send the email congratulating the user
            $this->mailer
                ->to($this->ion_auth->user($submission->owner)->row()->email)
                ->from("squad@tappyn.com")
                ->subject("Congratulations, you're submission won!")
                ->html($this->load->view('emails/submission_chosen', array('company_name' => $company_name), TRUE))
                ->send();

            // Tell the company they have successfully selected a winner!
            $this->responder->message(
                "A winner has been chosen!"
            )->respond();
            $this->user->attribute_points($submission->owner, $this->config->item('points_per_winning_submission'));
            $this->load->library('vote');
            $this->vote->dole_out_points($submission->id);
            return;
        }
        else
        {
            $this->responder->fail(
                $this->payout->errors() ? $this->payout->errors() : "An unknown error occured"
            )->code(500)->respond();
            return;
        }
    }
}

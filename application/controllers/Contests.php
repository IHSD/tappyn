<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends CI_Controller
{
    protected $params = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/navbar');
        $this->load->model('contest');
        $this->load->model('submission');
        $this->load->library('submission_library');
        $this->data['footer'] = 'templates/footer';
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
            $this->data['contests'] = $contests;
            $this->data['pagination_links'] = $this->pagination->create_links();
        }
        $this->load->view('contests/index', $this->data);
    }

    /**
     * Fetch a single contest
     * @param  integer $id
     * @return void
     */
    public function show($cid)
    {
        $contest = $this->contest->get($cid);
        if($this->ion_auth->logged_in())
        {
            $this->data['can_submit'] = $this->submission_library->userCanSubmit($this->ion_auth->user()->row()->id, $cid);
        }
        else {
            $this->data['can_submit'] = true;
        }

        if(!$contest)
        {
            $this->session->set_flashdata('error', 'That contest does not exist');
            redirect('contests/index', 'refresh');
        }
        $this->data['contest'] = $contest;
        $this->data['genders'] = array(
            'GENDER' => 'Gender',
            0 => 'All',
            1 => "Male",
            2 => "Female"
        );
        $this->data['ages'] = array(
            0 => '18-24',
            1 => '25-34',
            2 => '35-44',
            3 => '45+'
        );
        $this->load->view('contests/show', $this->data);
    }
    /**
     * Create a new contest, or render the creation form
     * @return void
     */
    public function create()
    {
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(3))
        {
            $this->session->set_flashdata('error', 'You must be logged in as a company to launch a contest');
            redirect('contests/index', 'refresh');
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
            $this->session->set_flashdata('message', $this->contest->messages());
            redirect("users/dashboard", "refresh");
        }
        else
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : false));

            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('title')
            );
            $this->data['audience_description'] = array(
                'name' => 'audience_description',
                'id' => 'audience_description',
                'type' => 'text',
                'value' => $this->form_validation->set_value('audience_description')
            );
            $this->data['how_your_different'] = array(
                'name' => 'how_your_different',
                'id' => 'how_your_different',
                'type' => 'text',
                'value' => $this->form_validation->set_value('how_your_different')
            );
            $this->data['objective'] = array(
                'name' => 'objective',
                'id' => 'objective',
                'type' => 'text',
                'value' => $this->form_validation->set_value('objective')
            );
            $this->data['location'] = array(
                'name' => 'location',
                'id' => 'location',
                'type' => 'text',
                'value' => $this->form_validation->set_value('location')
            );
            $this->data['age_range'] = array(
                'name' => 'age_range',
                'id' => 'age_range',
                'type' => 'text',
                'value' => $this->form_validation->set_value('age_range')
            );
            $this->data['gender'] = array(
                'name' => 'gender',
                'id' => 'gender',
                'type' => 'text',
                'value' => $this->form_validation->set_value('gender')
            );
            $this->data['format'] = array(
                'name' => 'format',
                'id' => 'format',
                'type' => 'text',
                'value' => $this->form_validation->set_value('format')
            );

            $this->data['platforms'] = array(
                'facebook' => 'Facebook',
                'google' => 'Google',
                'general' => 'General',
                'twitter' => 'Twitter'
            );
            $this->data['objectives'] = array(
                'engagement' => 'Increase Engagement',
                'website_clicks' => 'Send People to your Website',
                'app_installs' => 'App Installs',
            );
            $this->load->view('contests/create', $this->data);
        }
    }

    public function submissions($cid)
    {
        $contest = $this->contest->get($cid);
        $submissions = $this->contest->submissions($cid);
        if($this->ion_auth->logged_in())
        {
            $this->data['can_submit'] = $this->submission_library->userCanSubmit($this->ion_auth->user()->row()->id, $cid);
        } else {
            $this->data['can_submit'] = true;
        }
        $this->data['contest'] = $contest;
        $this->data['submissions'] = $submissions;
        $this->load->view('submissions/index', $this->data);
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

    /**
     * Edit and update a contest
     * @return void
     */
    public function edit()
    {

    }
}

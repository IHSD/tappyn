<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends CI_Controller
{
    protected $params = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/navbar');
        $this->load->model('contest');
        $this->data['footer'] = 'templates/footer';
    }

    /**
     * View all available contests
     * @return void
     */
    public function index()
    {
        $config['base_url'] = base_url().'contests/index';
        $config['total_rows'] = $this->contest->count($this->params);
        $config['per_page'] = 10;
        $this->pagination->initialize($config);

        $contests = $this->contest->fetchAll($this->params, 'start_time', 'desc', 10);
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
    public function show($id)
    
        $contest = $this->contest->get($id);
        if($contest !== FALSE)
        {
            $this->data['contest'] = $contest;
            $this->load->view('contests/show', $this->data);
        } else {
            $this->session->set_flashdata('error', 'That contest does not exist');
            redirect('contests/index');
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
            $this->session->set_flashdata('error', 'You must be logged in as a company to launch a contest');
            redirect('contests/index', 'refresh');
        }

        $this->form_validation->set_rules('time_length', 'time_length', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('submission_limit', 'submission_limit', 'required');
        $this->form_validation->set_rules('prize', 'prize', 'required');
        $this->form_validation->set_rules('objective', 'objective', 'required');
        $this->form_validation->set_rules('platform', 'platform', 'required');

        if($this->form_validation->run() == true)
        {
            // Do some preliminary formatting
            $data = array(
                'title'                 => $this->input->post('title'),
                'submission_limit'      => $this->input->post('submission_limit'),
                'owner'                 => $this->ion_auth->user()->row()->id,
                'time_length'           => $this->input->post('time_length'),
                'stop_time'             => date('Y-m-d H:i:s', strtotime('+'.$this->input->post('time_length'))),
                'prize'                 => $this->input->post('prize'),
                'platform'              => $this->input->post('platform'),
                'objective'             => $this->input->post('objective')
            );
        }
        if($this->form_validation->run() == true && ($cid = $this->contest->create($data)))
        {
            $this->session->set_flashdata('message', $this->contest->messages());
            redirect("contests/show/{$cid}");
        }
        else
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : false));

            $this->data['options'] = array(
                3 => '3 days',
                7 => '7 days',
                14 => '2 weeks'
            );
            $this->data['limits'] = array(
                '50' => 50,
                '100' => 100,
                '250' => 250,
                '500' => 500
            );

            $this->data['prizes'] = array(
                '100.00' => '100.00',
                '250.00' => '250.00',
                '500.00' => '500.00',
                '1000.00' => '1000.00'
            );

            $this->data['platforms'] = array(
                'facebook' => 'Facebook',
                'google' => 'Google',
                'trending' => 'Trending',
                'tagline' => 'Tagline'
            );

            $this->data['time_length'] = array(
                'name' => 'time_length',
                'id' => 'time_length',
                'type' => 'text',
                'value' => $this->form_validation->set_value('time_length')
            );
            $this->data['title'] = array(
                'name' => 'title',
                'id' => 'title',
                'type' => 'text',
                'value' => $this->form_validation->set_value('title')
            );
            $this->data['submission_limit'] = array(
                'name' => 'submission_limit',
                'id' => 'submission_limit',
                'type' => 'text',
                'value' => $this->form_validation->set_value('submission_limit')
            );
            $this->data['prize'] = array(
                'name' => 'prize',
                'id' => 'prize',
                'type' => 'text',
                'value' => $this->form_validation->set_value('prize')
            );
            $this->data['objective'] = array(
                'name' => 'objective',
                'id' => 'objective',
                'type' => 'text',
                'value' => $this->form_validation->set_value('objective')
            );
            $this->data['platform'] = array(
                'name' => 'platform',
                'id' => 'platform',
                'type' => 'text',
                'value' => $this->form_validation->set_value('platform')
            );
            $this->load->view('contests/create', $this->data);
        }
    }

    public function submissions()
    {

    }

    /**
     * Edit and update a contest
     * @return void
     */
    public function edit()
    {

    }
}

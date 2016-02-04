<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends CI_Controller
{
    protected $params = null;

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
        $config['per_page'] = 1;
        $this->pagination->initialize($config);

        $contests = $this->contest->fetchAll($this->params);
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
    {
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
        $this->form_validation->set_rules('start_time', 'start_time', 'required');
        $this->form_validation->set_rules('stop_time', 'stop_time', 'required');
        $this->form_validation->set_rules('title', 'title', 'required');
        $this->form_validation->set_rules('submission_limit', 'submission_limit', 'required');
        $this->form_validation->set_rules('prize', 'prize', 'required');
        $this->form_validation->set_rules('objective', 'objective', 'required');
        $this->form_validation->set_rules('platform', 'platform', 'required');

        if($this->form_validation->run() == true)
        {
            // Do some preliminary formatting
        }
        if($this->form_validation->run() == true && ($cid = $this->contest->create(array())))
        {
            $this->session->set_flashdata('message', $this->contest->messages());
            redirect("contests/show/{$cid}");
        }
        else
        {
            $this->data['error'] = (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : array('An unknown error occured')));
            $this->data['start_time'] = array(
                'name' => 'start_time',
                'id' => 'start_time',
                'type' => 'text',
                'value' => $this->form_validation->set_value('start_time')
            );
            $this->data['stop_time'] = array(
                'name' => 'stop_time',
                'id' => 'stop_time',
                'type' => 'text',
                'value' => $this->form_validation->set_value('stop_time')
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

    /**
     * Edit and update a contest
     * @return void
     */
    public function edit()
    {

    }
}

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

    }

    /**
     * Edit and update a contest
     * @return void
     */
    public function edit()
    {

    }
}

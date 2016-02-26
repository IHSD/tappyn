<?php defined("BASEPATH") or exit("No direct script access allowed");

class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
          // redirect('/', 'refresh');
        }
        if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        	$this->load->view('templates/navbar');
        }
        $this->load->model('submission');
        $this->load->model('contest');
        $this->load->library('vote');
    }

    public function data()
    {
        // Generate KPI
        $this->data['total_users'] = $this->ion_auth->users()->num_rows();
        $this->data['users_count'] = $this->ion_auth->users(2)->num_rows();
        $this->data['companies_count'] = $this->ion_auth->users(3)->num_rows();
        // Get new signups
        $this->data['signups'] = $this->user->select('DATE(FROM_UNIXTIME(created_on)) as date, COUNT(*) as count')->where(array('created_on >' => strtotime('-7 days')))->group_by('date')->fetch()->result();
        $this->data['submissions'] = $this->submission->select('DATE(created_at) as date, COUNT(*) as count')->where(array('created_at >' => strtotime('-7 days')))->group_by('date')->fetch()->result(false);
        $this->data['contests'] = $this->contest->select('DATE(created_at) as date, COUNT(*) as count')->where(array('created_at >' => strtotime('-7 days')))->group_by('date')->fetch()->result();
        $this->data['votes'] = $this->vote->select('DATE(FROM_UNIXTIME(created_at)) as date, COUNT(*) as count')->where(array('FROM_UNIXTIME(created_at) >' => strtotime('-7 days')))->group_by('date')->fetch()->result();
        $this->responder->data($this->data)->respond();
    }

    public function index()
    {
        $this->load->view('admin/index.php');
    }
}

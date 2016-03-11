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
            if($this->input->get('navbar') != 'hide')
            {
        	       $this->load->view('templates/navbar');
            }
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

    public function email()
    {
        $path = 'emails/';
        $template = $this->input->get('template');
        if($this->input->get('auth'))
        {
            $path = 'auth/email/';
        }
        $args = array(
            'text' => lorem(),
            'headline' => substr(lorem(), 0, 45),
            'email' => 'rob@ihsdigital.com',
            'contest' => 'Some random contest',
            'company' => 'Nike',
            'cid'     => 1,
            'contests' => array(
                array(
                    'company' => uniqid(),
                    'description' => substr(lorem(), 0, 45)
                )
            ),
            'platform' => 'facebook',
            'objective' => 'webiste_clicks',
            'display_type' => 'right_column',
            'start_time' => date('Y-m-d H:00 A'),
            'stop_time' => date('Y-m-d H:00 A', strtotime('+7 days')),
            'payment_method' => 'card_abcxyz123890',
            'last_4'    => '1234',
            'expiration_date'       => '01/2015',
            'brand' => 'Visa'
        );
        $this->load->view($path.$template.'.php', $args);
    }

    public function docs()
    {

    }
}

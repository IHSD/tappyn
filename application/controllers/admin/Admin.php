<?php defined("BASEPATH") or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('secrets', TRUE);
        $this->admin_api_key = $this->config->item('admin_api_key', 'secrets');
        $this->admin_api_key = $this->config->item('admin_api_secret', 'secrets');
        $this->load->helper('url');
    }

    public function login_as_user()
    {

    }

    public function test_post_contest_package($contest_id, $email = 'rob')
    {
        $contest = $this->db->select('*')->from('contests')->where('id', $contest_id)->get()->row();
        $company = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->get()->row();
        $submission = $this->db->select('*')->from('payouts')->join('submissions', 'payouts.submission_id = submissions.id', 'left')->where('payouts.contest_id', $contest->id)->get()->row();
        var_dump($this->mailer->to($email.'@ihsdigital.com')
                     ->from('squad@tappyn.com')
                     ->subject('Your Tappyn Ad')
                     ->html($this->load->view('emails/post_contest_package', array(
                         'cname' => $company->name,
                         'contest' => $contest,
                         'submission' => $submission
                     ), TRUE))
                     ->send());
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Mailing extends CI_Controller
{
    protected $email_data = array();

    public function __construct()
    {
        parent::__construct();
        if( ! is_cli() )
        {
            die('Invalid request');
        }
        $this->load->library('mailer');
        $this->config->load('emails', TRUE);
        $this->email_config = $this->config->item('email_program', 'emails');

    }

    public function execute()
    {
        $queue = $this->db->select('*')->from('mailing_queue')->where('processing', 0)->get()->result();

        foreach($queue as $job)
        {
            $this->db->where('id', $job->id)->update('mailing_queue', array('processing' => 1));

            $subject = FALSE;
            $continue = true;
            // First lets get any associated data with this email
            switch($job->email_type)
            {
                case 'contest_closing':
                    // Get data for the email
                    $contest = $this->db->select('*')->from('contests')->where('id', $job->object_id)->limit(1)->get();
                    if(!$contest || $contest->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid contest supplied"]');
                        $continue = false;
                        continue;
                    }
                    $contest = $contest->row();
                    $contest->owner = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->get()->row();

                    // Set our subject and any additional data
                    $subject = sprintf($this->email_config[$job->email_type]['subject'], is_null($contest->owner->name) ? "This awesome" : $contest->owner->name.'s' );
                    $this->email_data['contest'] = $contest;

                break;

                case 'post_contest_package':
                    $contest = $this->db->select('*')->from('contests')->where('id', $job->object_id)->limit(1)->get();
                    if(!$contest || $contest->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid contest supplied"]');
                        $continue = false;
                        continue;
                    }
                    $contest = $contest->row();
                    $contest->owner = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->get()->row();

                    // Set our subject and any additional data
                    $subject = sprintf($this->email_config[$job->email_type]['subject'], is_null($contest->owner->name) ? "This awesome" : $contest->owner->name.'s' );
                    $this->email_data['contest'] = $contest;
                break;

                case 'winner_announced':
                    $contest = $this->db->select('*')->from('contests')->where('id', $job->object_id)->get();
                    if(!$contest || $contest->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid contest supplied"]');
                        $continue = false;
                        continue;
                    }
                    $contest = $contest->row();
                    $contest->owner = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->get()->row();
                    $subject = sprintf($this->email_config[$job->email_type]['subject'], is_null($contest->owner->name) ? "They " : $contest->owner->name);
                    $this->email_data['contest'] = $contest;
                    $this->email_data['company'] = $contest->owner;
                break;

                case 'mailing_list_conf':

                break;

                case 'sign_up_conf':
                    $user = $this->db->select('*')->from('users')->where('id', $job->object_id)->limit(1)->get();
                    if(!$user || $user->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid user supplied"]');
                        $continue = false;
                        continue;
                    }
                    $user = $user->row();
                    $this->email_data['activation'] = is_null($user->activation_code) ? 'activation' : $user->activation_code;
                    $this->email_data['uid'] = $user->id;
                break;

                case 'contest_completed':
                    $contest = $this->db->select('*')->from('contests')->where('id', $job->object_id)->get();
                    if(!$contest || $contest->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid contest supplied"]');
                        $continue = false;
                        continue;
                    }
                    $contest = $contest->row();
                    $this->email_data['company'] = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->get()->row();

                    $this->email_data['contest'] = $contest;
                break;

                case 'submission_confirmation':
                    $submission = $this->db->select('*')->from('submsssions')->where('id', $job->object_id)->get();
                    if(!$submission || $submission->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid submission supplied"]');
                        $continue = false;
                        continue;
                    }
                    $submission->contest = $this->db->select('*')->from('contests')->join('profiles', 'contests.owner = profiles.id', 'left')->where('contests.id', $submission->contest_id)->get()->row();
                    $this->email_data['submissions'] = $submission;
                break;

                case 'company_sign_up_conf':
                    $company = $this->db->select('*')->from('profiles')->where('id', $job->object_id)->get();
                    if(!$company || $company->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid company supplied"]');
                        $continue = false;
                        continue;
                    }
                    $this->email_data['company'] = $company->row();
                break;

                case 'submission_chosen':
                    $company = $this->db->select('*')->from('profiles')->where('id', $job->object_id)->get();
                    if(!$company || $company->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid company supplied"]');
                        $continue = false;
                        continue;
                    }
                    $this->email_data['company'] = $company->row();
                break;

                case 'contest_receipt':
                    $this->load->library('stripe/stripe_charge_library');
                    $contest = $this->db->select('*')->from('contests')->where('id', $job->object_id)->get();
                    if(!$contest || $contest->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid contest supplied"]');
                        $continue = false;
                        continue;
                    }
                    $contest = $contest->row();
                    $this->email_data['contest'] = $contest;
                    $this->email_data['voucher'] = FALSE;
                    $this->email_data['charge'] = FALSE;
                    $voucher = $this->db->select('*')->from("voucher_uses")->where('contest_id', $contest->id)->get()->row();

                    if(!empty($voucher))
                    {
                        $this->email_data['voucher'] = $this->db->select('*')->from('vouchers')->where('id', $voucher->voucher_id)->get()->row();
                    }
                    $charge = $this->db->select('*')->from('stripe_charges')->where('contest_id', $contest->id)->get()->row();
                    if(!empty($charge))
                    {
                        $this->email_data['charge'] = $this->stripe_charge_library->retrieve($charge->charge_id);
                        if(!$this->email_data['charge'])
                        {
                            $this->error_out($job->id, '["Error fetching charge details from Stripe :: '.$this->stripe_charge_library->errors().'"]');
                            $continue = false;
                            continue;
                        }
                    }
                    $this->email_data['company'] = $this->db->select('*')->from('profiles')->where('id', $contest->owner)->get()->row();
                break;

                case 'payout_receipt':
                    $payout = $this->db->select('*')->from('payouts')->where('id', $job->object_id)->get();
                    if(!$payout || $payout->num_rows() == 0)
                    {
                        $this->error_out($job->id, '["Invalid payout supplied"]');
                        $continue = false;
                        continue;
                    }
                    $payout = $payout->row();
                    $this->email_data['payout'] = $payout;
                break;

                case 'contact_conf':

                break;

                default :
                    $this->error_out($job->id, '["Invalid email type '.$job->email_type.' supplied"]');
                    $continue = false;
                    continue;

            }
            if(!$continue) continue;
            // Set base data for every email
            foreach($this->email_config[$job->email_type]['additional_data'] as $key => $value)
            {
                $this->email_data[$key] = $value;
            }
            $this->email_data['query_string'] = $this->email_config[$job->email_type]['query_string'];
            $this->email_data['query_string']['eid'] = $job->id;
            if(!$subject) $subject = $this->email_config[$job->email_type]['subject'];
            // Generate our html email based on template and our
            try {
                $generated_html = $this->load->view($this->email_config[$job->email_type]['template'], $this->email_data, TRUE);
            } catch(Exception $e) {
                $this->error_out($job->id, '["Could not generate email html body::'.$e->getMessage().'"]');
                continue;
            }
            if(!$generated_html)
            {
                $this->error_out($job->id, '["Template missing from requested location"]');
                continue;
            }
            // Clean up before we try and send the email
            $this->email_data = array();

            /**
             * Now we actually send the email using our generated stuff
             */
            $this->mailer->to($job->recipient)
                         ->from($this->email_config[$job->email_type]['from'])
                         ->subject($subject)
                         ->html($generated_html);
            if($this->mailer->send())
            {
                $this->db->where('id', $job->id)->update('mailing_queue', array(
                    'sent_at' => time(),
                ));
            } else {
                $this->db->where('id', $job->id)->update('mailing_queue', array(
                    'failure_reason' => $this->mailer->errors()
                ));
            }
        }
    }

    public function error_out($id, $error)
    {
        $this->db->where('id', $id)->update('mailing_queue', array('failure_reason' => $error));
    }

    public function test_mail()
    {
        $this->db->insert('mailing_queue', array(
            'queued_at' => time(),
            'sent_at' => NULL,
            'failure_reason' => NULL,
            'recipient' => 'rob@ihsdigital.com',
            'recipient_id' => 2128,
            'email_type' => "test",
            "processing" => 0,
            'object_type' => null,
            'object_id' => null
        ));
    }
}

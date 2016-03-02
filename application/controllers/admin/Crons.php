<?php defined("BASEPATH") or exit('No direct script access allowed');

class Crons extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!is_cli())
        {
            die();
        }
    }

    public function index()
    {

    }

    /**
     * Ended contest cron job
     *
     * Pulls all contests that have recently ended and have not had winners
     * selected yet, and sends an email to those owners4
     *
     * Schedule : HOURLY @ :05
     * @return void
     */
    public function contest_ended()
    {
        $stop_time = date('Y-m-d H:00:00', strtotime('-1 hour'));
        $check = $this->db->select('id, DATE_FORMAT(stop_time, "%Y-%m-%d %H:00:00") as end_time')->from('contests')->where(array('end_time' => $stop_time))->get();
        if($check && $check->num_rows() > 0)
        {
            foreach($check->result() as $contest)
            {
                // Check if the payout has been generated yet. If it has, we just skip it,
                // else we notify the contest owner that they have to select a winner
                $payout_check = $this->db->select('id')->from('payouts')->where('contest_id', $contest->id)->get();
                if(!$payout_check || $payout_check->num_rows() > 0) continue;

                // Send the email to the contest owner
                $email_data = array(
                    'company' => $this->ion_auth->user($contest->owner)->row()->first_name.' '.$this->ion_auth->user($contest->owner)->row()->last_name,
                    'contest_id' => $contest->id,
                    'eid' => $this->mailer->id($this->ion_auth->user($contest->owner)->row()->email, 'contest_completed')
                );
                $this->mailer->to($this->ion_auth->user($contest->owner))
                             ->from('squad@tappyn.com')
                             ->subject('Your contest has completed!')
                             ->html($this->load->view('emails/contest_needs_winner'), $email_data, TRUE)
                             ->send();
            }
        }
    }

    /**
     * Fetch all contests that have been recently launched. Let's find the ones with the highest number of submissions,
     * and select the top 5 to notify users with.
     *
     * Schedule : WEEKLY ON WEDNESDAY @ 12:00
     *
     * @return void
     */
    public function new_contests()
    {
        
    }
}

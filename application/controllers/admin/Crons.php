<?php defined("BASEPATH") or exit('No direct script access allowed');

class Crons extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!is_cli())
        {
        //    die();
        }
    }

    public function index()
    {

    }

    public function push_notification()
    {
        $users = $this->db->select('*')->from('users_groups')->where('group_id', 2)->get();
        foreach($users->result() as $user)
        {
            if(!$this->db->insert('notifications', array(
                'user_id' => $user->user_id,
                'type' => 'new_update',
                'created' => time(),
                'read_at' => NULL,
                'read' => 0,
                'object_type' => 'update',
                'object_id' => 0,
            ))) {
                die($this->db->error()['message']);
            }
        }
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
    public function facebook()
    {
        $this->load->model('contest');
        $contests = $this->contest->where(array(
            'paid' => 1,
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s')
        ))->order_by('id', 'asc')->fetch()->result();

        foreach($contests as $contest)
        {
            echo "=======================================\n";
            echo "        Contest {$contest->id}\n";
            echo "=======================================\n\n";
            echo "Submission   Shares\n";
            echo "-------------------\n";

            $submissions = $this->contest->submissions($contest->id);
            foreach($submissions as $sub)
            {
                $data = json_decode(file_get_contents('https://graph.facebook.com/?id='.base_url().'submissions/share/'.$sub->id));
                echo " {$sub->id}         ".(isset($data->shares) ? $data->shares : 0)."\n";
                if(isset($data->shares) && $data->shares > 0)
                {
                    $this->db->where('id', $sub->id)->update('submissions', array('shares' => $data->shares));
                }
            }
        }
    }

    public function generate_users($filename)
    {
        $filename = urldecode($filename);

        $data = $this->csv_to_array($filename);

        $this->email_activation = FALSE;
        $tables = $this->config->item('tables','ion_auth');
        $identity_column = $this->config->item('identity','ion_auth');
        $this->data['identity_column'] = $identity_column;

        foreach($data as $datum)
        {
            $password = bin2hex(openssl_random_pseudo_bytes(4));
            $email    = strtolower($datum['email']);
            $identity = ($identity_column==='email') ? $email : $this->input->post('identity');

			$name_chunks = explode(' ', $this->input->post('name'));
            $additional_data = array(
                'first_name' => $datum['first_name'],
                'last_name'  => $datum['last_name'],
				'age'		=> NULL,
				'gender' 	=> NULL,
            );

            if(!$this->ion_auth->register($identity, $password, $email, $additional_data, array(2)))
            {
                echo $email.' | '.$this->ion_auth->errors()."\n";
            } else {
                $this->mailer->to($email)
                             ->from('alek@tappyn.com')
                             ->subject('Your Tappyn Account is ready!')
                             ->html($this->load->view('emails/onboard', array('username' => $datum['first_name'], 'password' => $password), TRUE))
                             ->send();
            }
        }
    }

    private function csv_to_array($filename='', $delimiter=',')
    {
    	if(!file_exists($filename) || !is_readable($filename))
    		return FALSE;

    	$header = NULL;
    	$data = array();
    	if (($handle = fopen($filename, 'r')) !== FALSE)
    	{
    		while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
    		{
    			if(!$header)
    				$header = $row;
    			else
    				$data[] = array_combine($header, $row);
    		}
    		fclose($handle);
    	}
    	return $data;
    }
}

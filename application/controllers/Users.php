<?php defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in to access this area")->code(401)->respond();
            exit();
        }
        $this->load->model('user');
        $this->load->model('submission');
        $this->load->library('payout');
        $this->load->model('contest');
        $this->load->library('stripe/stripe_account_library');
        $this->stripe_account_id = $this->user->account($this->ion_auth->user()->row()->id);
    }

    /**
     * Generate a users dashboard.
     *
     * If its a company, we pull in all the contests.
     * Other wise we pull in a users submissions
     * @return void
     */
    public function dashboard()
    {
        $this->load->library('vote');
        // If company, redirect to companies controller
        if($this->ion_auth->in_group(3))
        {
            redirect("companies/dashboard");
        }
        $this->data['status'] = 'all';

        if($this->input->get('type') === 'winning')
        {
            // Get all winnign submissions from payout table
            $payout_ids = array();
            $payouts = $this->payout->fetch(array('user_id' => $this->ion_auth->user()->row()->id));
            if($payouts)
            {
                foreach($payouts as $payout)
                {
                    $payout_ids[] = $payout->submission_id;
                }
            }
            if(empty($payout_ids))
            {
                $this->responder->data(array())->respond();
                return;
            }
            // then find submissions whose id exist in payout table
            $this->submission->where_in('id', $payout_ids);
        }
        else if($this->input->get('type') === 'completed')
        {
            // Join contests and find ones where contest is still active
            $this->submission->join('contests', "submissions.contest_id = contests.id", 'left');
            $this->submission->where('contests.stop_time <', date('Y-m-d H:i:s'));
        }
        else if($this->input->get('type') === 'in_progress')
        {
            $this->submission->join('contests', 'submissions.contest_id = contests.id', 'left');
            $this->submission->where(array(
                'contests.stop_time >' => date('Y-m-d H:i:s'),
                'contests.start_time <' => date('Y-m-d H:i:s')
            ));
        }
        // Make sure we only grab ones belonging to the user
        $this->submission->where('submissions.owner', $this->ion_auth->user()->row()->id);
        $submissions = $this->submission->fetch();
        if($submissions !== FALSE)
        {
            $submissions = $submissions->result();
            foreach($submissions as $submission)
            {

                $submission->votes = (int)$this->vote->select('COUNT(*) as count')->where(array('submission_id' => $submission->id))->fetch()->row()->count;
            }
            $this->responder->data(
                array(
                    'submissions' => $submissions
                )
            )->respond();
        } else {
            $this->responder->fail('There was an error fetching your dashboard')->code(500)->respond();
        }
    }

    /**
     * View a users profile
     * @return void
     * @todo Remove previous image on update of company_logo
     */
    public function profile()
    {
        if(!$this->ion_auth->in_group(2))
        {
            redirect("companies/profile");
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {

            $data = array(
                'age' => $this->input->post('age_range'),
                'gender' => $this->input->post('gender'),
                'state' => $this->input->post('state'),
                'school' => $this->input->post('school')
            );

            if(!$this->user->saveProfile($this->ion_auth->user()->row()->id, $data))
            {
                $this->responder
                    ->fail("There was an error updating your profile")
                    ->code(500)
                    ->respond();
                return;
            } else {
                $this->responder
                    ->message(
                        'Profile was successfully updated'
                    )
                    ->data(array(
                        'profile' => $this->user->profile($this->ion_auth->user()->row()->id)
                    ))
                    ->respond();
                return;
            }
        } else {
            $profile = $this->user->profile($this->ion_auth->user()->row()->id);
            $this->responder->data(array(
                'profile' => $profile
            ))->respond();
            return;
        }
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in to access this area.")->code(401)->respond();
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
        if ($this->ion_auth->in_group(3)) {
            redirect("api/v1/companies/dashboard?type={$this->input->get('type')}");
        }
        $this->data['status'] = 'all';

        if ($this->input->get('type') === 'winning') {
            // Get all winnign submissions from payout table
            $payout_ids = array();
            $payouts = $this->payout->fetch(array('user_id' => $this->ion_auth->user()->row()->id));
            if ($payouts) {
                foreach ($payouts as $payout) {
                    $payout_ids[] = $payout->submission_id;
                }
            }
            if (empty($payout_ids)) {
                $this->responder->data(array())->respond();
                return;
            }
            // then find submissions whose id exist in payout table
            $this->submission->where_in('id', $payout_ids);
        } else if ($this->input->get('type') === 'completed') {
            // Join contests and find ones where contest is still active
            $this->submission->join('contests', "submissions.contest_id = contests.id", 'left');
            $this->submission->where('contests.stop_time <', date('Y-m-d H:i:s'));
        } else if ($this->input->get('type') === 'in_progress') {
            $this->submission->join('contests', 'submissions.contest_id = contests.id', 'left');
            $this->submission->where(array(
                'contests.stop_time >' => date('Y-m-d H:i:s'),
                'contests.start_time <' => date('Y-m-d H:i:s'),
            ));
        }
        // Make sure we only grab ones belonging to the user
        $this->submission->where('submissions.owner', $this->ion_auth->user()->row()->id);
        $submissions = $this->submission->fetch();
        if ($submissions !== false) {
            $submissions = $submissions->result();
            $profile = $this->user->profile($this->ion_auth->user()->row()->id);
            foreach ($submissions as $submission) {
                $submission->avatar_url = $profile->avatar_url;
                $submission->votes = (int) $this->vote->select('COUNT(*) as count')->where(array('submission_id' => $submission->id))->fetch()->row()->count;
                $submission->contest = $this->contest->get($submission->contest_id);
                $submission->company = $this->user->profile($submission->owner)->name;
                if ($submission->contest->use_attachment == 1) {
                    $submission->attachment = $submission->contest->attachment;
                }
            }
            $this->responder->data(
                array(
                    'submissions' => $submissions,
                )
            )->respond();
        } else {
            $this->responder->fail('There was an error fetching your dashboard.')->code(500)->respond();
        }
    }

    /**
     * View a users profile
     * @return void
     * @todo Remove previous image on update of company_logo
     */
    public function profile()
    {
        $this->load->library('interest');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->ion_auth->in_group(2)) {
                // Can the user change theyre age / gender / location?
                $data = array();
                $uid = $this->ion_auth->user()->row()->id;
                if ($this->interest->add_user_interests($uid, $this->input->post('interests')) === false) {
                    $this->responder
                        ->fail("At least three interests.")
                        ->code(500)
                        ->respond();
                    return;
                }

                if ($this->user->canEditAge($uid) && $this->input->post('age')) {
                    $data['age'] = $this->input->post('age');
                }

                if ($this->user->canEditGender($uid) && $this->input->post('gender')) {
                    $data['gender'] = $this->input->post('gender');
                }

                if ($this->user->canEditLocation($uid) && $this->input->post('state')) {
                    $data['state'] = $this->input->post('state');
                }

                if ($this->input->post('first_name') || $this->input->post('last_name')) {
                    $userdata = array();
                    if ($this->input->post('first_name')) {
                        $userdata['first_name'] = $this->input->post('first_name');
                    }

                    if ($this->input->post('last_name')) {
                        $userdata['last_name'] = $this->input->post('last_name');
                    }

                    $this->ion_auth->update($this->ion_auth->user()->row()->id, $userdata);
                }
                if ($this->input->post('avatar_url')) {
                    $data['avatar_url'] = $this->input->post('avatar_url');
                }
                if (empty($data)) {
                    $this->responder
                        ->message(
                            'Your profile was successfully updated.'
                        )
                        ->data(array(
                            'profile' => $this->user->profile($this->ion_auth->user()->row()->id),
                        ))
                        ->respond();
                    return;
                }
                if (!$this->user->saveProfile($this->ion_auth->user()->row()->id, $data)) {
                    $this->responder
                        ->fail("There was an error updating your profile.")
                        ->code(500)
                        ->respond();
                    return;
                } else {
                    $this->responder
                        ->message(
                            'Profile was successfully updated.'
                        )
                        ->data(array(
                            'profile' => $this->user->profile($this->ion_auth->user()->row()->id),
                        ))
                        ->respond();
                    return;
                }
            } else {
                $data = array(
                    'logo_url' => $this->input->post('logo_url'),
                    'mission' => $this->input->post('mission'),
                    'extra_info' => $this->input->post('extra_info'),
                    'name' => $this->input->post('company_name'),
                    'company_email' => $this->input->post('company_email'),
                    'company_url' => $this->input->post('company_url'),
                    'twitter_handle' => $this->input->post('twitter_handle'),
                    'facebook_url' => $this->input->post('facebook_url'),
                    'different' => $this->input->post('different'),
                    'summary' => $this->input->post('summary'),
                );

                if ($this->user->saveProfile($this->ion_auth->user()->row()->id, $data)) {
                    $this->responder->data(array('profile' => $this->user->profile($this->ion_auth->user()->row()->id)))->message("Profile successfully updated")->respond();
                } else {
                    $this->responder->fail(($this->user->errors() ? $this->user->errors() : "There was an error updating your profile"))->code(500)->respond();
                }
            }
        } else {
            $uid = $this->ion_auth->user()->row()->id;
            $profile = $this->user->profile($uid);
            $profile->first_name = $this->ion_auth->user()->row()->first_name;
            $profile->last_name = $this->ion_auth->user()->row()->last_name;
            $profile->company_name = $profile->name;
            $profile->interests = $this->interest->get_user_interests($uid);
            $this->responder->data(array(
                'profile' => $profile,
            ))->respond();
            return;
        }
    }

    public function stats()
    {
        $submissions = $this->submission->select('COUNT(*) as count')->where('owner', $this->ion_auth->user()->row()->id)->fetch()->row()->count;
        $upvotes = $this->vote->select('COUNT(*) as count')->where('user_id', $this->ion_auth->user()->row()->id)->fetch()->row()->count;
        $payouts = $this->payout->fetch(array('user_id' => $this->ion_auth->user()->row()->id));
        $won = count($payouts);
        $this->responder->data(array(
            'submissions' => $submissions,
            'upvotes' => $upvotes,
            'won' => $won,
        ))->respond();
    }

    public function upvoted()
    {
        $submissions = $this->vote->select('*')->join('submissions', 'votes.submission_id = submissions.id', 'left')->where('votes.user_id', $this->ion_auth->user()->row()->id)->order_by('votes.created_at', 'desc')->limit(50)->fetch();
        $subs = $submissions->result();
        foreach ($subs as $submission) {
            $submission->avatar_url = $this->db->select('avatar_url')->from('profiles')->where('id', $submission->owner)->limit(1)->get()->row()->avatar_url;
            $submission->owner = $this->db->select('first_name, last_name')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();
            $submission->votes = (int) $this->vote->select('COUNT(*) as count')->where(array('submission_id' => $submission->id))->fetch()->row()->count;
            $submission->contest = $this->contest->get($submission->contest_id);
            if ($submission->contest->use_attachment == 1) {
                $submission->attachment = $submission->contest->attachment;
            }
        }
        $this->responder->data(array('submissions' => $subs))->respond();
    }

    public function follow($cid)
    {
        $this->db_test = $this->load->database('master', true);
        $check = $this->db_test->select('*')->from('follows')->where(array('follower' => $this->ion_auth->user()->row()->id, 'following' => $cid))->limit(1)->get()->row()->count;
        if ($check == 0) {
            // Attempt to follow
            if ($this->db_test->insert('follows', array(
                'follower' => $this->ion_auth->user()->row()->id,
                'following' => $cid,
                'created' => time(),
            ))) {
                $this->responder->data()->respond();
            } else {
                $this->responder->fail("There was an error following that company")->code(500)->respond();
            }
        } else {
            $this->responder->fail("You already follow this company")->code(500)->respond();
            return;
        }
    }

    public function unfollow($cid)
    {
        $this->db_test = $this->load->database('master', true);
        if ($this->db_test->where(array('follower' => $this->ion_auth->user()->row()->id, 'following' => $cid))->delete('follows')) {
            $this->responder->data()->message("You are no longer following this company!");
        } else {
            $this->responder->fail("There was an error unfollowing this company")->code(500);
        }
        $this->responder->respond();
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends CI_Controller
{
    protected $params = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('contest');
        $this->load->model('submission');
        $this->load->library('submission_library');
        $this->load->library('mailer');
        $this->load->model('user');
        $this->load->library('vote');
        $this->load->library('interest');

    }

    /**
     * View all available contests
     * @return void
     */
    public function index($type = 'all')
    {
        $gender = null;
        $age = null;

        if ($this->ion_auth->logged_in()) {
            $profile = $this->user->profile($this->ion_auth->user()->row()->id);
            $gender = $profile->gender;
            $age = $profile->age;
        }

        $this->params = array(
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s'),
            'paid' => 1,
        );

        $has_more = false;

        $sql_interests = array();

        if ($type == 'interesting') {
            $interests = $this->load->library('interest');
            $this->interest->setDatabase($this->db);
            $this->interest->setUser($this->ion_auth->user()->row()->id);

            $ints = $this->interest->flatten($this->interest->tree()->children);
            foreach ($ints as $int) {
                if ($int->followed_by_user) {
                    $sql_interests[] = $int->name;
                }

            }

            if (empty($sql_interests)) {
                $this->responder->fail("You dont have any interests yet!")->code(500)->respond();
                return;
            }
        }
        if ($this->input->get('industry')) {
            $this->params['industry'] = $this->input->get('industry');
        }

        $config['base_url'] = base_url() . 'contests/index';
        $config['total_rows'] = $this->contest->count($this->params);
        $config['per_page'] = ($this->input->get('per_page') ? $this->input->get('per_page') : 20);
        $this->pagination->initialize($config);
        $limit = $config['per_page'];
        $offset = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $contests = $this->contest->fetchAll($this->params, 'start_time', 'asc', $limit, $offset, $sql_interests);

        if (($offset + $config['per_page']) < $config['total_rows']) {
            $has_more = true;
        }
        if ($contests !== false) {
            $this->responder->data(array(
                'contests' => $contests,
                'total_rows' => $config['total_rows'],
                'per_page' => (int) $config['per_page'],
                'has_more' => $has_more,
                'page' => $offset == 0 ? 1 : floor($offset / $config['per_page'] + 1),
            ))->respond();
        } else {
            $this->responder->fail("An unknown error occured")->code(500)->respond();
        }
    }

    /**
     * Contest leaderboard endpoint
     * @return void
     */
    public function leaderboard()
    {
        $this->params = array(
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s'),
            'paid' => 1,
        );

        if (!$this->contest->where($this->params)->fetch()) {
            $this->responder->fail("Server Error Occured")->code(500)->respond();
            return;
        }
        $contests = $this->contest->result();

        foreach ($contests as $contest) {
            $contest->votes = $this->vote->select('COUNT(*) as count')->where('contest_id', $contest->id)->fetch()->row()->count;
            $contest->submission_count = $this->contest->submissionsCount($contest->id);
            $contest->company = $this->user->profile($contest->owner);
            unset($contest->company->stripe_customer_id);
        }

        usort($contests, function ($a, $b) {
            return ((int) $b->votes > (int) $a->votes) ? -1 : 1;
        }
        );
        $this->responder->data(array('contests' => array_slice($contests, 0, 4)))->respond();

    }
    /**
     * Fetch a single contest
     *
     * Also, we log the impression so we can track views
     * @param  integer $id
     * @return void
     */
    public function show($cid)
    {
        $contest = $this->contest->get($cid);

        if (!$contest) {
            $this->responder->fail(
                "That contest does not exist"
            )->code(404)->respond();
        } else {
            $contest->views = $this->contest->views($cid);
            $this->ion_auth->logged_in() ?
            $contest->user_may_submit = $this->contest->mayUserSubmit($this->ion_auth->user()->row()->id, $cid) :
            $contest->user_may_submit = false;
            $this->responder->data(array(
                'contest' => $contest,
            ))->respond();
            $this->contest->log_impression($cid, $this->ion_auth->user()->row()->id);
        }
        $this->analytics->track(array(
            'event_name' => 'view_contest',
            'object_type' => 'contest',
            'object_id' => $cid,
        ));
    }

    /**
     * Show winner of contest_id
     *
     * @todo Add winning submission to response
     * @param  [type] $contest_id [description]
     * @return [type]             [description]
     */
    public function winner($contest_id)
    {
        $this->load->library('payout');
        $contest = $this->contest->get($contest_id);
        if (!$contest) {
            $this->responder->fail("That contest does not exist")->code(500)->respond();
            return;
        }

        if ($contest->stop_time < date('Y-m-d H:i:s')) {
            $contest->status = 'ended';
        } else {
            $contest->status = 'active';
        }

        if ($payout = $this->payout->exists(array('contest_id' => $contest_id))) {
            $winner = $this->submission->where('id', $payout->submission_id)->limit(1)->fetch()->row();
            $owner = $winner->owner;
            $winner->first_name = $this->ion_auth->user($owner)->row()->first_name;
            $winner->last_name = $this->ion_auth->user($owner)->row()->last_name;
            $winner->votes = (int) $this->vote->select('COUNT(*) as count')->where(array('submission_id' => $winner->id))->fetch()->row()->count;
            $contest->payout = true;
            $this->responder->data(array('contest' => $contest, 'winner' => $winner))->respond();
        } else {
            $contest->payout = false;
            $this->responder->data(array('contest' => $contest, 'winner' => false))->respond();
        }
    }

    /**
     * Create a new contest, or render the creation form
     * @return void
     */
    public function create()
    {
        $update = false;
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(3)) {
            $this->responder->fail("You need to be logged in as a company to create campaigns.")->code(403)->respond();
            return;
        }

        $this->form_validation->set_rules('different', 'How Your Different', 'required');
        $this->form_validation->set_rules('objective', 'Objective', 'required');
        $this->form_validation->set_rules('platform', 'Format', 'required');
        $this->form_validation->set_rules('summary', 'Summary', 'required');
        //$this->form_validation->set_rules('display_type', 'Display Type', 'required');

        if ($this->form_validation->run() == true) {
            $this->config->load('upvote');
            $start_time = ($this->input->post('start_time') ? $this->input->post('start_time') : date('Y-m-d H:i:s', strtotime('+1 hour')));
            $age = $this->input->post('age');
            $gender = $this->input->post('gender') ? $this->input->post('gender') : 0;
            $location = $this->input->post('location');
            if (is_array($location)) {
                $location = implode(',', $location);
            }
            $industry = $this->input->post('industry');
            if (is_array($industry)) {
                $industry = array_slice($industry, 0, 3);
                $industry = implode(',', $industry);
            }
            $location_box = $this->input->post('location_box');
            if (is_array($location_box)) {
                $location_box = $location_box['id'];
            }
            // Do some preliminary formatting
            $data = array(
                'location' => $location,
                'additional_info_box' => $this->input->post('additional_info_box'),
                'location_box' => $location_box,
                'summary' => $this->input->post('summary'),
                'additional_info' => $this->input->post('additional_info'),
                'different' => $this->input->post('different'),
                'objective' => $this->input->post('objective'),
                'platform' => $this->input->post('platform'),
                'gender' => $this->input->post('gender'),
                'owner' => $this->ion_auth->user()->row()->id,
                'min_age' => $this->input->post('min_age'),
                'max_age' => $this->input->post('max_age'),
                'industry' => $industry,
                'emotion' => $this->input->post('emotion'),
                'display_type' => $this->input->post('display_type'),
                'submission_limit' => $this->input->post('submission_limit') ? $this->input->post('submission_limit') : 30,
                'prize' => $this->config->item('default_payout_per_contest'),
            );

            $images = array();
            if ($this->input->post('additional_image_1'));
            $images[] = $this->input->post('additional_image_1');
            if ($this->input->post('additional_image_2'));
            $images[] = $this->input->post('additional_image_2');
            if ($this->input->post('additional_image_3'));
            $images[] = $this->input->post('additional_image_3');
            if (!empty($images)) {
                $data['additional_images'] = json_encode($images);
            }

            error_log(json_encode($data));
            $cid = $this->contest->create($data);
        }

        if ($this->form_validation->run() == true && $cid) {
            $message = $update ? 'updated' : 'created';
            $this->responder->message("Contest successfully {$message}")->data(array('id' => $cid))->respond();
            $this->analytics->track(array(
                'event_name' => "contest_creation",
                'object_type' => "contest",
                'object_id' => $cid,
            ));
            $profile_data = array();
            $profile = $this->ion_auth->profile();
            if (is_null($profile->mission)) {
                $profile_data['mission'] = $this->input->post('audience');
            }

            if (is_null($profile->different)) {
                $profile_data['different'] = $this->input->post('different');
            }

            if (is_null($profile->summary)) {
                $profile_data['summary'] = $this->input->post('summary');
            }

            if (is_null($profile->company_email)) {
                $profile_data['company_email'] = $this->input->post('company_email');
            }

            if (is_null($profile->company_url)) {
                $profile_data['company_url'] = $this->input->post('company_url');
            }

            if (!empty($profile_data)) {
                $this->user->saveProfile($this->ion_auth->user()->row()->id, $profile_data);
            }
        } else {
            $this->responder->fail(
                (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : 'An unknown error occured'))
            )->code(500)->respond();
        }
    }

    /**
     * Set a submission as the winner of the contest
     * @param  integer $cid Contest ID
     * @param  integer $sid Submission ID
     * @return void
     */
    public function select_winner($cid)
    {
        $this->load->library('payout');
        $this->load->model('user');
        $sid = $this->input->post('submission');

        if (!$this->ion_auth->logged_in()) {
            $this->responder->fail("You must be logged in to perform this action")->code(401)->respond();
            return;
        }
        // Check that submission exists
        if (!$submission = $this->submission->get($sid)) {
            $this->responder->fail("That submission does not exist")->code(500)->respond();
            return;
        }
        // Check that contest exists
        else if (!$contest = $this->contest->get($cid)) {
            $this->responder->fail("We couldn't find contest with id {$cid}")->code(500)->respond();
            return;
        }
        // Check that the contest has ended
        else if ($contest->stop_time > date('Y-m-d H:i:s')) {
            $this->responder->fail("This contest has not finished yet")->code(500)->respond();
            return;
        }
        $company_name = $this->db->select('name')->from('profiles')->where("id", $contest->owner)->get();
        if ($company_name) {
            $company_name = $company_name->row()->name;
        } else {
            $company_name = '';
        }
        // Check that we are admin or the ccontest owner

        if ($this->ion_auth->user()->row()->id != $contest->owner) {
            if (!$this->ion_auth->is_admin()) {
                $this->responder->fail('You must own the contest to select a winner')->code(403)->respond();
                return;
            }
        }
        $payout = $this->payout->exists(array('contest_id' => $cid));
        if ($payout) {
            $this->responder->fail("An ad has already been chosen as the winner.")->code(500)->respond();
            return;
        }
        // Attempt to create the payouts
        if ($pid = $this->payout->create($cid, $sid)) {
            // Send the email congratulating the user

            // Tell the company they have successfully selected a winner!
            $this->responder->message(
                "A winner has been chosen!"
            )->respond();
            /**$this->mailer->queue($this->ion_auth->user()->row()->email, $this->ion_auth->user()->row()->id, 'post_contest_package', 'contest', $contest->id);**/
            $this->user->attribute_points($submission->owner, $this->config->item('points_per_winning_submission'));
            $this->load->library('vote');
            $this->vote->dole_out_points($submission->id);

            // We have to notify the winner they won, and all other users that it ended but they didnt win
            $eid = $this->mailer->id($this->ion_auth->user()->row()->email, 'submission_chosen');
            $submissions = $this->db->select('users.*, submissions.id as sub_id, users.id as uid')->from('submissions')->join('users', 'submissions.owner = users.id', 'left')->where('contest_id', $contest->id)->get()->result();
            foreach ($submissions as $entry) {
                if ($entry->sub_id == $this->input->post('submission')) {
                    // Notify the winner
                    $this->mailer->queue($entry->email, $entry->uid, 'submission_chosen', 'contest', $contest->id);
                } else {
                    // Let them know it ended, but they didnt win
                    $this->mailer->queue($entry->email, $entry->uid, 'winner_announced', 'contest', $contest->id);
                }
            }
            $this->analytics->track(array(
                'event_name' => "winner_selected",
                'object_type' => "contest",
                'object_id' => $cid,
            ));
            $this->notification->create($submission->owner, 'submission_chosen', 'submission', $submission->id);

            return;
        } else {
            $this->responder->fail(
                $this->payout->errors() ? $this->payout->errors() : "An unknown error occured"
            )->code(500)->respond();
            return;
        }
    }

    public function update($id)
    {
        $update = false;
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(3)) {
            $this->responder->fail("You need to be logged in as a company to create contests")->code(403)->respond();
            return;
        }
        $this->form_validation->set_rules('different', 'How Your Different', 'required');
        $this->form_validation->set_rules('objective', 'Objective', 'required');
        $this->form_validation->set_rules('platform', 'Format', 'required');
        $this->form_validation->set_rules('summary', 'Summary', 'required');
        //$this->form_validation->set_rules('display_type', 'Display Type', 'required');

        if ($this->form_validation->run() == true) {
            $start_time = ($this->input->post('start_time') ? $this->input->post('start_time') : date('Y-m-d H:i:s', strtotime('+1 hour')));
            $age = $this->input->post('age');
            $gender = $this->input->post('gender') ? $this->input->post('gender') : 0;
            $location = $this->input->post('location');
            if (is_array($location)) {
                $location = implode(',', $location);
            }
            $industry = $this->input->post('industry');
            if (is_array($industry)) {
                $industry = array_slice($industry, 0, 3);
                $industry = implode(',', $industry);
            }
            $location_box = $this->input->post('location_box');
            if (is_array($location_box)) {
                $location_box = $location_box['id'];
            }
            // Do some preliminary formatting
            $data = array(
                'location' => $location,
                'additional_info_box' => $this->input->post('additional_info_box'),
                'location_box' => $location_box,
                'summary' => $this->input->post('summary'),
                'additional_info' => $this->input->post('additional_info'),
                'different' => $this->input->post('different'),
                'objective' => $this->input->post('objective'),
                'platform' => $this->input->post('platform'),
                'gender' => $this->input->post('gender'),
                'owner' => $this->ion_auth->user()->row()->id,
                'min_age' => $this->input->post('min_age'),
                'max_age' => $this->input->post('max_age'),
                'industry' => $industry,
                'start_time' => $start_time,
                'stop_time' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'emotion' => $this->input->post('emotion'),
                'display_type' => $this->input->post('display_type'),
                'submission_limit' => $this->input->post('submission_limit') ? $this->input->post('submission_limit') : 30,
            );

            $images = array();
            if ($this->input->post('additional_image_1'));
            $images[] = $this->input->post('additional_image_1');
            if ($this->input->post('additional_image_2'));
            $images[] = $this->input->post('additional_image_2');
            if ($this->input->post('additional_image_3'));
            $images[] = $this->input->post('additional_image_3');
            if (!empty($images)) {
                $data['additional_images'] = json_encode($images);
            }

            // Check that they own the contest
            $contest = $this->contest->get($id);
            if (!$contest || ($contest->owner !== $this->ion_auth->user()->row()->id)) {
                $this->responder->fail("You do not own this contest brody")->code(403)->respond();
                return;
            }
            $cid = $this->contest->update($id, $data);
        }

        if ($this->form_validation->run() == true && $cid) {
            $message = $update ? 'updated' : 'created';
            $this->responder->message("Contest successfully updated")->data(array('id' => $cid))->respond();
            $this->analytics->track(array(
                'event_name' => "contest_creation",
                'object_type' => "contest",
                'object_id' => $cid,
            ));
            $profile_data = array();
            $profile = $this->ion_auth->profile();
            if (is_null($profile->mission)) {
                $profile_data['mission'] = $this->input->post('audience');
            }

            if (is_null($profile->different)) {
                $profile_data['different'] = $this->input->post('different');
            }

            if (is_null($profile->summary)) {
                $profile_data['summary'] = $this->input->post('summary');
            }

            if (is_null($profile->company_email)) {
                $profile_data['company_email'] = $this->input->post('company_email');
            }

            if (is_null($profile->company_url)) {
                $profile_data['company_url'] = $this->input->post('company_url');
            }

            if (!empty($profile_data)) {
                $this->user->saveProfile($this->ion_auth->user()->row()->id, $profile_data);
            }
        } else {
            $this->responder->fail(
                (validation_errors() ? validation_errors() : ($this->contest->errors() ? $this->contest->errors() : 'An unknown error occured'))
            )->code(500)->respond();
        }
    }

    public function delete($id = null)
    {
        if (is_null($id)) {
            $this->responder->fail("You must provide a campaign to delete")->code(500)->respond();
            return;
        }

        if ($this->contest->delete($id)) {
            $this->responder->data()->message("Campaign successfully deleted")->respond();
        } else {
            $this->responder->fail(($this->contest->errors() ? $this->contest->errors() : "There was an error deleting your contest"))->code(500)->respond();
        }
    }
}

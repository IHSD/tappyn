<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('contest', 'submission', 'impression', 'vote'));

    }

    public function index()
    {
        $date = date('Y-m-d H:i:s');
        $params = array(
            ContestFields::START_TIME.' >' => $date,
            ContestFields::STOP_TIME.' <' => $date,
            ContestFields::PAID => 1
        );
        $contests = Contest::all($params);
        foreach($contests as $contest)
        {
            $contest->submission_count = Submission::count(array(SubmissionFields::CONTEST_ID => $contest->id));
        }
        $this->response->data(array(
            'contests' => $contests
        ))->respond();
    }

    public function show($id)
    {
        $contest = Contest::get($id);
        if(!$contest)
        {
            $this->response->fail("That contest does not exist")->code(404);
        } else {

            // Get some contest metadata
            $contest->votes = Vote::count(array(VoteFields::CONTEST_ID => $contest->id));
            $contest->impressions = Impression::count(array(ImpressionFields::CONTEST_ID => $contest->id));

            // Pull in and sort all of our submissions
            $submissions = Submission::find(array(SubmissionFields::CONTEST_ID => $contest->id));
            foreach($submissions as $submission)
            {
                $submission->votes = Vote::count(array(VoteFields::SUBMISSION_ID => $submission->id));
                if($this->user) $submission->user_has_voted = Vote::find(array(VoteFields::SUBMISSION_ID => $submission->id, VoteFields::USER_ID => $this->user->id));
            }
            $contest->submissions = $this->sort($submissions);

            // Respond with our contest
            $this->response->data(array(
                'contest' => $contest
            ));
            Hook::trigger('viewed_contest', array('contest_id' => $contest, 'user' => $this->user));
        }
        $this->response->respond();
    }

    public function create()
    {
        Hook::trigger('contest_creation_started');

        $this->form_validation->set_rules('title', 'Title', 'required');
        if($this->form_validation->run() === TRUE)
        {
            $contest = new Contest();

            $contest->setData(array(
                ContestFields::OWNER => $this->user->id,
                ContestFields::OBJECTIVE => $this->input->post('objective'),
                ContestFields::PLATFORM => $this->input->post('platform'),
                ContestFields::AUDIENCE => $this->input->post('audience'),
                ContestFields::DIFFERENT => $this->input->post('different'),
                ContestFields::SUMMARY => $this->input->post('summary'),
                ContestFields::INDUSTRY => $this->input->post('industry'),
                ContestFields::EMOTION => $this->input->post('emotion'),
                ContestFields::DISPLAY_TYPE => $this->input->post('display_type'),
            ));
            try {
                $contest->save();
            } catch(Exception $e) {
                $this->response->fail($e->getMessage())->code(500)->respond();
                return;
            }

            Hook::trigger('contest_created', array('object' => 'contest', 'id' => $cid));
            redirect('contests/'.$cid, 'refresh');
        }
        else
        {
            $this->response->fail(
                    ($errors = $this->form_validation->error_array()) ? reset($errors) : "An unknown error occured"
            )->code(500)->respond();
        }
    }

    public function update()
    {

    }

    public function delete()
    {

    }

    function sort($submissions)
    {
        return $submissions;
    }
}

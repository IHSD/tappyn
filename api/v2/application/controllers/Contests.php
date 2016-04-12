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
            $contest->submissions = Submission::find(array(SubmissionFields::CONTEST_ID => $contest->id));
            $contest->votes = Vote::find(array(VoteFields::CONTEST_ID => $contest->id));
            foreach($contest->submissions as $submission)
            {
                $submission->votes = Vote::find(array(VoteFields::SUBMISSION_ID => $submission->id));
                //$submission->user_has_voted = Vote::hasUserVoted(array('submission_id' => $submission->id, 'user_id'));
            }
            $contest->impressions = Impression::count(array(ImpressionFields::CONTEST_ID => $contest->id));

            $this->response->data(array(
                'contest' => $contest
            ));
        }
        $this->response->respond();
    }

    public function create()
    {
        if($this->form_validation->run('contest:create') === TRUE)
        {
            $contest = new Contest();

            $contest->setData($data);
            try {
                $contest->save();
            } catch(Exception $e) {
                $this->response->fail($e->getMessage())->code(500)->respond();
                return;
            }
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
}

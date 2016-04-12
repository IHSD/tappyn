<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('contest', 'submission', 'impression'));

    }

    public function index()
    {
        $date = date('Y-m-d H:i:s');
        $params = array(
            'stop_time >' => $date,
            'start_time <' => $date,
            'paid' => 0
        );
        $contests = Contest::all($params);
        foreach($contests as $contest)
        {
            $contest->submission_count = Submission::count(array('contest_id' => $contest->id));
        }
        $this->response->data(array(
            'contests' => $contests
        ))->respond();
    }

    public function show($id)
    {
        $this->load->model('contest');
        $contest = Contest::get($id);
        if(!$contest)
        {
            $this->response->fail("That contest does not exist")->code(404);
        } else {
            $contest->submissions = Submission::find(array('contest_id' => $contest->id));
            foreach($contest->submissions as $submission)
            {
                // $submission->votes = Vote::find(array('submission_id' => $submission_id));
                // $submission->user_has_voted = Vote::hasUserVoted(array('submission_id' => $submission->id, ''))
            }
            $contest->impressions = Impression::count(array('contest_id' => $contest->id));

            $this->response->data(array(
                'contest' => $contest
            ));
        }
        $this->response->respond();
    }

    public function create()
    {
        $contest = new Contest();
        $data = array(ContestFields::EMOTION => 'test');
        $contest->setData($data);
        try {
            $contest->save();
        } catch(Exception $e) {
            $this->response->fail($e->getMessage())->code(500)->respond();
            return;
        }
        $this->response->data(array('contest' => $contest->data()))->respond();

    }

    public function update()
    {

    }

    public function delete()
    {

    }
}

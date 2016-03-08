<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('submission');
        $this->load->model('contest');
        $this->load->library('ion_auth');
        $this->load->library('submission_library');
        $this->load->model('ion_auth_model');
        $this->load->model('user');
        $this->load->library('mailer');
        $this->load->library('vote');
    }

    /**
     * Get all submissions for a contest
     * @param  int $contest_id
     * @return void
     */
    public function index($contest_id)
    {
        $submissions = $this->contest->submissions($contest_id);
        foreach($submissions as $submission)
        {
            $submission->votes = (int)$this->vote->select('COUNT(*) as count')->where(array('submission_id' => $submission->id))->fetch()->row()->count;
            $submission->user_may_vote = (bool)$this->ion_auth->logged_in() ? $this->vote->mayUserVote($submission->id, $this->ion_auth->user()->row()->id) : true;
        }
        /** Sort our submissions on upvotes **/
        usort($submissions, function($a, $b)
            {
                return ((int) $b->votes > (int) $a->votes) ? -1 : 1;
            }
        );

        $contest = $this->contest->get($contest_id);
        if($contest->stop_time < date('Y-m-d H:i:s'))
        {
            $contest->status = 'ended';
        } else {
            $contest->status = 'active';
        }
        $contest->views = $this->contest->views($contest_id);
        $contest->additional_images = json_decode($contest->additional_images);
        $this->responder->data(array(
            'submissions' => $submissions,
            'contest' => $contest
        ))->respond();
    }

    /**
     * Create a new submission
     *
     * @return void
     */
    public function create($contest_id)
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail(
                "You must be logged in to create submissions"
            )->code(401)->respond();
            return;
        }

        if($this->ion_auth->in_group(3))
        {
            $this->responder->fail(
                "Only creators are allowed to submit to contests"
            )->code(403)->respond();
            return;
        }

        if($sid = $this->submission_library->create($contest_id, $this->input->post('headline'), $this->input->post('text')))
        {
            $this->responder->message(
                "You're submission has succesfully been created"
            )->respond();
            $this->user->attribute_points($this->ion_auth->user()->row()->id, $this->config->item('points_per_submission'));
            $this->analytics->track(array(
                'event_name' => "submission_create",
                'object_type' => "submission",
                'object_id' => $sid
            ));
        }
        else {
            $this->responder->fail(
                ($this->submission_library->errors() ? $this->submission_library->errors() : 'An unknown error occured')
            )->code(500)->respond();
        }
    }

    public function leaderboard()
    {
        $leaderboard_size = $this->config->item('leaderboard_limit');
        // Get the top 5 submissions
        $check = $this->vote->select('COUNT(*) as count, submission_id')->group_by('submission_id')->order_by('count', 'DESC')->limit($leaderboard_size)->fetch();
        if(!$check)
        {
            $this->responder->fail("An unexpected error occured")->code(500)->respond();
            return;
        }
        $submissions = array();
        foreach($check->result() as $sub)
        {
            $submission = $this->submission->get($sub->submission_id);
            $submission->votes = (int)$this->vote->select('COUNT(*) as count')->where(array('submission_id' => $submission->id))->fetch()->row()->count;
            $submission->user_may_vote = (bool)$this->ion_auth->logged_in() ? $this->vote->mayUserVote($submission->id, $this->ion_auth->user()->row()->id) : true;
            $submission->owner = $this->db->select('first_name, last_name')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();
            $submissions[] = $submission;
        }
        $this->responder->data(array(
            'submissions' => $submissions
        ))->respond();
    }

    public function share($id)
    {
        $submission = $this->submission->get($id);
        // Create image based on submission
        $this->load->view('submissions/share', array('submission' => $submission));
    }

    public function test()
    {
        $n1 = $_GET['n1'];
        $n2 = $_GET['n2'];

        Header ("Content-type: image/jpeg");
        $image = imageCreateFromJPEG("images/someimage.jpg");
        $color = ImageColorAllocate($image, 255, 255, 255);

        // Calculate horizontal alignment for the names.
        $BoundingBox1 = imagettfbbox(13, 0, 'ITCKRIST.TTF', $n1);
        $boyX = ceil((125 - $BoundingBox1[2]) / 2); // lower left X coordinate for text
        $BoundingBox2 = imagettfbbox(13, 0, 'ITCKRIST.TTF', $n2);
        $girlX = ceil((107 - $BoundingBox2[2]) / 2); // lower left X coordinate for text

        // Write names.
        imagettftext($image, 13, 0, $boyX+25, 92, $color, 'ITCKRIST.TTF', $n1);
        imagettftext($image, 13, 0, $girlX+310, 92, $color, 'ITCKRIST.TTF', $n2);

        // Return output.
        ImageJPEG($image, NULL, 93);
        //ImageDestroy($image);
    }
}

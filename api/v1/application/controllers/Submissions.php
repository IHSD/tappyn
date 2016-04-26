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
        $this->load->library('s3');
        $this->load->library('image');
    }

    /**
     * Get all submissions for a contest
     * @param  int $contest_id
     * @return void
     */
    public function index($contest_id)
    {
        $submissions = $this->submission->where(array('contest_id' => $contest_id))->fetch()->result();
        foreach($submissions as $submission)
        {
            $submission->votes = (int)$this->vote->select('COUNT(*) as count')->where(array('submission_id' => $submission->id))->fetch()->row()->count;
            $submission->user_may_vote = (bool)$this->ion_auth->logged_in() ? $this->vote->mayUserVote($submission->id, $this->ion_auth->user()->row()->id) : true;
        }
        /** Sort our submissions on upvotes **/
        usort($submissions, function($a, $b)
            {
                return ((int) $b->votes < (int) $a->votes) ? -1 : 1;
            }
        );

        /*
         * If the user has created one of the submissions, we then push it to
         * the front of the results automatically
         */
        $usub = FALSE;
        foreach($submissions as $key => $submission)
        {
            $submissions[$key]->owner = $this->db->select('first_name, last_name')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();
            if($this->ion_auth->logged_in())
            {
                if($submission->owner == $this->ion_auth->user()->row()->id)
                {
                    $submissions[$key]->user_owned = TRUE;
                    $usub = $submissions[$key];
                    unset($submissions[$key]);
                }
            }
            if($usub)
            {
                $submissions = array_values(array($usub) + $submissions);
            }
        }

        $contest = $this->contest->get($contest_id);

        if($contest->stop_time < date('Y-m-d H:i:s'))
        {
            $contest->status = 'ended';
        } else {
            $contest->status = 'active';
        }

        $contest->views = $this->contest->views($contest_id);
        $contest->user_has_submitted = FALSE;
        $contest->user_may_submit = FALSE;

        if($this->ion_auth->logged_in())
        {
            $contest->user_has_submitted = $this->contest->hasUserSubmitted($this->ion_auth->user()->row()->id, $contest_id);
            $contest->user_may_submit = $this->contest->mayUserSubmit($this->ion_auth->user()->row()->id, $contest_id);
        }

        // Process the images array, and remove all nulls
        $addtl_images = json_decode($contest->additional_images);
        if(!is_null($addtl_images))
        {
            foreach($addtl_images as $key => $image)
            {
                if(is_null($image)) unset($addtl_images[$key]);
            }
        } else $addtl_images = array();

        if(!empty($addtl_images))
        {
            $contest->additional_images = array_values($addtl_images);
        } else $contest->additional_images = FALSE;

        $this->responder->data(array(
            'submissions' => $submissions,
            'contest' => $contest
        ))->respond();

        $this->contest->log_impression($contest_id);
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

        if($this->ion_auth->user()->row()->active == 0)
        {
            $this->responder->fail(
                "Your account has not been verified yet"
            )->code(500)->respond();
            return;
        }
        $uid = $this->ion_auth->user()->row()->id;
        if(!$this->contest->mayUserSubmit($uid, $contest_id))
        {
            $this->responder->fail(
                "Unfortunately, you are not eligible for this contest!"
            )->code(500)->respond();
            return;
        }
        $contest = $this->contest->get($contest_id);
        if(!$contest)
        {
            $this->responder->fail(
                "That contest does not exist!"
            )->code(500)->respond();
            return;
        }

        $attachment_url = NULL;
        if($this->input->post('photo'))
        {

            $filename = hash('sha256', uniqid());
            $thumb = $this->image->compress($this->input->post('photo'));

            if($this->image->upload($this->input->post('photo'), $filename) && $this->image->upload($thumb, $filename.'_thumb'))
            {
                $attachment_url = "https://tappyn.s3.amazonaws.com/".$filename;
            } else
            {
                $this->responder->fail(
                "There was an error uploading your image"
                )->code(500)->respond();
                return;
            }
        }

        if($sid = $this->submission_library->create($contest_id, $this->input->post('headline'), $this->input->post('text'), $this->input->post('link_explanation'), $attachment_url))
        {
            // If this submission would cap the contest, we set the contesst to end in 1 day!
            if(($contest->submission_count + 1) == $contest->submission_limit)
            {
                $this->contest->update($contest_id, array('stop_time' => date('Y-m-d H:i:S', strtotime('+1 day'))));
            }

            $this->responder->message(
                "You're submission has succesfully been created"
            )->respond();
            $this->user->attribute_points($this->ion_auth->user()->row()->id, $this->config->item('points_per_submission'));
            $this->analytics->track(array(
                'event_name' => "submission_create",
                'object_type' => "submission",
                'object_id' => $sid
            ));

            $this->notification->create($this->ion_auth->user()->row()->id, 'submission_confirmed', 'submission', $sid);
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
        // Get a list of all active contests
        $ids = array();
        $contests = $this->contest->where(array(
            'paid' => 1,
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s')
        ))->fetch()->result();

        // We dont have any active contests, so ets just exit out with success
        if(empty($contests))
        {
            $this->responder->message("There currently aren't any active contests")->respond();
            return;
        }
        foreach($contests as $contest)
        {
            $ids[] = (int)$contest->id;
        }

        $check = $this->vote->select('COUNT(*) as count, submission_id, contest_id')->where_in('contest_id', $ids)->group_by('submission_id')->order_by('count', 'DESC')->limit($leaderboard_size)->fetch();

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
        $submission->votes = (int)$this->vote->select('COUNT(*) as count')->where(array('submission_id' => $submission->id))->fetch()->row()->count;
        $submission->owner = $this->db->select('first_name, last_name')->from('users')->where('id', $submission->owner)->limit(1)->get()->row();

        // If the png hasnt been created, we generate an image
        if(!file_exists(FCPATH."public/img/subs/sub_{$id}.png"))
        {
            $my_img = imagecreate( 1200, 630 );
            $background = imagecolorallocate( $my_img, 255, 255, 255 );
            $text_colour = imagecolorallocate( $my_img, 255, 92, 0 );
            $black = imagecolorallocate($my_img, 0,0,0);
            $text_length = 50;
            $sig = wordwrap($submission->text, $text_length, "<br />", true);
            $text = str_replace('<br />', "\n", $sig);
            imagettftext($my_img, 45, 0, 45, 100, $text_colour, FCPATH.'fonts/Lato-Medium.ttf', $submission->headline);
            imagettftext($my_img, 35, 0, 45,210, $black, FCPATH.'fonts/Lato-Thin.ttf', $text);
            imagesetthickness ( $my_img, 5 );
            imagerectangle($my_img, 0, 0, 1200, 630, $text_colour);
            //ImageArc($my_img, 1120, 550, 125, 125, 0, 360, $text_colour);

            imagepng( $my_img , FCPATH."public/img/subs/sub_{$id}.png");
            imagecolordeallocate( $my_img, $text_colour );
            imagecolordeallocate( $my_img, $background );
            imagedestroy( $my_img );
        }
        // Create image based on submission
        $this->load->view('submissions/share', array('submission' => $submission));
        $this->analytics->track(array(
            'event_name' => "submission_share_view",
            'object_type' => "submission",
            'object_id' => $id
        ));

        $this->db->where('id', $id);
        $this->db->set('share_clicks', 'share_clicks + 1', FALSE);
        $this->db->update('submissions');
    }

    public function rate()
    {
        if(!$this->input->post("submission_id") || !$this->input->post('rating'))
        {
            $this->responder->fail("You must provide a submission")->code(500)->respond();
            return;
        }
        $sid = $this->input->post('submission_id');
        $rating = (int)$this->input->post('rating');
        $submission = $this->submission->get($sid);
        if(!$submission)
        {
            $this->responder->fail("That submission does not exist")->code(500)->respond();
            return;
        }
        $contest = $this->contest->get($submission->contest_id);
        if(!$contest || $contest->owner !== $this->ion_auth->user()->row()->id)
        {
            $this->responder->fail("That contest doesnt exist, or you dont have permission")->code(500)->respond();
            return;
        }
        if($this->db->where('id', $sid)->update('submissions', array('rating' => $rating)))
        {
            $this->responder->respond();
        } else {
            $this->responder->fail("There was an error rating your submission")->code(500)->respond();
        }
    }
}

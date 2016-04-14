<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('contest', 'submission', 'impression', 'vote'));
        Hook::register_model(array($this->contest, $this->submission, $this->impression, $this->vote));

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
            Hook::trigger('viewed_contest', array('contest_id' => $contest->id, 'user_id' => $this->user->id));
        }
        $this->response->respond();
    }

    public function create()
    {
        Hook::trigger('contest_creation_started');

        // Set our form validation rules and error messages
        $this->form_validation->set_rules('platform', 'Platform', array('required', array( 'platform_callable', array($this->contest, 'validate_platform'))));
        $this->form_validation->set_rules('objective', "Objective", array('required', array( 'objective_callable', array($this->contest, 'validate_objective'))));
        $this->form_validation->set_rules('industry', "Industry", array('required', array( 'industry_callable', array($this->contest, 'validate_industry'))));
        $this->form_validation->set_rules('emotion', "Emotion", array(array('emotion_callable', array($this->contest, 'validate_emotion'))));
        $this->form_validation->set_rules('display_type', "Display Type", array('required', array('display_callable', array($this->contest, 'validate_display_type'))));
        $this->form_validation->set_message('display_callable', "Invalid display type supplied");
        $this->form_validation->set_message('industry_callable', "Invalid industry supplied");
        $this->form_validation->set_message('emotion_callable', "Invalid emotion supplied");
        $this->form_validation->set_message('objective_callable', "Invalid objective supplied");
        $this->form_validation->set_message('platform_callable', "Invalid platform supplied");

        // If our form has passed validation, we attempt to create a contest
        if($this->form_validation->run() === TRUE)
        {
            $contest = new Contest();

            $images = array();
            if($this->input->post('additional_image_1')); $images[] = $this->input->post('additional_image_1');
            if($this->input->post('additional_image_2')); $images[] = $this->input->post('additional_image_2');
            if($this->input->post('additional_image_3')); $images[] = $this->input->post('additional_image_3');
            if(!empty($images)) $data['additional_images'] = json_encode($images);

            $contest->setData(array(
                ContestFields::OWNER             => $this->user->id,
                ContestFields::OBJECTIVE         => $this->input->post('objective'),
                ContestFields::PLATFORM          => $this->input->post('platform'),
                ContestFields::AUDIENCE          => $this->input->post('audience'),
                ContestFields::DIFFERENT         => $this->input->post('different'),
                ContestFields::SUMMARY           => $this->input->post('summary'),
                ContestFields::INDUSTRY          => $this->input->post('industry'),
                ContestFields::EMOTION           => $this->input->post('emotion'),
                ContestFields::DISPLAY_TYPE      => $this->input->post('display_type'),
                ContestFields::ADDITIONAL_IMAGES => $images
            ));

            if(!$contest->save())
            {
                $this->response->fail($contest->errors() ? $contest->errors() : "An unknown error occured")->code(500)->respond();
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
        Hook::trigger('contest_created', array('object' => 'contest', 'id' => 1));
    }

    public function update($contest_id)
    {
        $contest = Contest::get($contest_id);
        if(!$contest)
        {
            $this->response->fail("That contest does not exist")->code(404)->respond();
            return;
        }
        // Set our form validation rules and error messages
        $this->form_validation->set_rules('platform', 'Platform', array('required', array( 'platform_callable', array($this->contest, 'validate_platform'))));
        $this->form_validation->set_rules('objective', "Objective", array('required', array( 'objective_callable', array($this->contest, 'validate_objective'))));
        $this->form_validation->set_rules('industry', "Industry", array('required', array( 'industry_callable', array($this->contest, 'validate_industry'))));
        $this->form_validation->set_rules('emotion', "Emotion", array(array('emotion_callable', array($this->contest, 'validate_emotion'))));
        $this->form_validation->set_rules('display_type', "Display Type", array('required', array('display_callable', array($this->contest, 'validate_display_type'))));
        $this->form_validation->set_message('display_callable', "Invalid display type supplied");
        $this->form_validation->set_message('industry_callable', "Invalid industry supplied");
        $this->form_validation->set_message('emotion_callable', "Invalid emotion supplied");
        $this->form_validation->set_message('objective_callable', "Invalid objective supplied");
        $this->form_validation->set_message('platform_callable', "Invalid platform supplied");

        $images = array();
        if($this->input->post('additional_image_1')); $images[] = $this->input->post('additional_image_1');
        if($this->input->post('additional_image_2')); $images[] = $this->input->post('additional_image_2');
        if($this->input->post('additional_image_3')); $images[] = $this->input->post('additional_image_3');
        if(!empty($images)) $data['additional_images'] = json_encode($images);

        // If our form has passed validation, we attempt to create a contest
        if($this->form_validation->run() === TRUE)
        {
            $update_data = array(
                ContestFields::OBJECTIVE            => $this->input->post('objective'),
                ContestFields::PLATFORM             => $this->input->post('platform'),
                ContestFields::EMOTION              => $this->input->post('emotion'),
                ContestFields::DISPLAY_TYPE         => $this->input->post('display_type'),
                ContestFields::SUMMARY              => $this->input->post('summary'),
                ContestFields::ADDITIONAL_IMAGES    => $images,
            );
            $contest->setData($update_data);
            if($contest->save())
            {
                Hook::trigger("contest_updated", array('object' => 'contest', 'id' => $contest_id));
                redirect('contests/'.$contest_id, 'refresh');
            }
            else
            {
                $this->response->fail($contest->errors())->code(500);
            }
        }
        else
        {
            $this->response->fail(
                ($errors = $this->form_validation->error_array()) ? reset($errors) : "An unknown error occured"
            )->code(500);
        }
        $this->response->respond();



    }

    public function delete($contest_id)
    {
        $contest = Contest::get($contes)t_id);
        if(!$contest)
        {
            $this->response->fail("That contest does not exist")->code(500)->respond();
            return;
        }
        $this->response->respond();
        // if($contest->delete())
        // {
        //     Hook::trigger('contest_deleted', array('object' => 'contest', 'id' => $contest_id));
        //     $this->response->respond();
        // }
        // else
        // {
        //     $this->response->fail($contest->errors() ? $contest->errors() : "An unknown error occured")->code(500)->repond();
        // }
    }

    function sort($submissions)
    {
        return $submissions;
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submission_library
{
    protected $errors = false;

    public function __construct()
    {
        $this->load->model('submission');
        $this->load->model('contest');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    /**
     * Create a contest submission
     * @param  array $data Fields required for creation
     * @return boolean
     */
    public function create($cid, $headline = NULL, $text = NULL, $link_exp = NULL)
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->errors = "You must be logged in to create submissions";
            return false;
        }

        if($this->ion_auth->in_group(3))
        {
            $this->errors = "Only creators are allower to submit to contests";
            return false;
        }

        // Get the contest, and then dynamically change form validation rules, based on the type of the contest
        $contest = $this->contest->get($cid);
        if(!$contest)
        {
            $this->errors = "We couldnt find the contest you were looking for";
            return false;
        }

        if($contest->submission_count >= 50)
        {
            $this->errors = "We're sorry but this contest has reached its submission limit";
            return false;
        }

        if($contest->stop_time < date('Y-m-d H:i:s'))
        {
            $this->errors = "We're sorry but this contest has already ended";
            return false;
        }
        if(!$this->submission_library->userCanSubmit($this->ion_auth->user()->row()->id, $contest->id))
        {
            $this->errors = "You have already submitted to this contest";
            return false;
        }

        $data = array(
            'owner' => $this->ion_auth->user()->row()->id,
            'contest_id' => $contest->id,
            'headline' => $headline,
            'text' => $text,
            'link_explanation' => $link_exp
        );

        $success = false;
        // Generate / validate fields based on the platform type
        if(!$this->userCanSubmit($data['owner'], $data['contest_id']))
        {
            $this->error = 'You have already put in a submission for that contest';
            return false;
        }

        if($id = $this->submission->create($data))
        {
            $email_data = array(
                'headline' => $this->input->post('headline'),
                'text' => $this->input->post('text'),
                'email' => ($this->ion_auth->user() ? $this->ion_auth->user()->row()->email : false),
                'contest' => $contest->title,
                'company' => $contest->company->name,
                'eid'    => $this->mailer->id($this->ion_auth->user()->row()->email, 'submission_successful')
            );
            $this->mailer->to($this->ion_auth->user()->row()->email)
                         ->from('squad@tappyn.com')
                         ->subject("Your submission was created!")
                         ->html($this->load->view('emails/submission_success', $email_data, TRUE))
                         ->send();
            return $id;
        }
        return FALSE;
    }

    /**
     * Ensure that the user has not already submitted to the contest
     * @param  integer $uid
     * @param  integer $contest_id
     * @return boolean
     */
    public function userCanSubmit($uid, $contest_id)
    {
        $check = $this->db->select('*')->from('submissions')->where(array('contest_id' => $contest_id, 'owner' => $uid))->limit(1)->get();
        if($check && $check->num_rows() > 0)
        {
            return FALSE;
        }
        return TRUE;
    }

    public function errors()
    {
        return $this->errors;
    }
}

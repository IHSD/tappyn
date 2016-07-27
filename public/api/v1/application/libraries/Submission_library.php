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
    public function create($cid, $headline = null, $text = null, $link_explanation = null, $attachment = null)
    {
        if (!$this->ion_auth->logged_in()) {
            $this->errors = "You must be logged in to create submissions";
            return false;
        }

        if ($this->ion_auth->in_group(3)) {
            $this->errors = "Only creators are allower to submit to contests";
            return false;
        }

        // Get the contest, and then dynamically change form validation rules, based on the type of the contest
        $contest = $this->contest->get($cid);
        if (!$contest) {
            $this->errors = "We couldnt find the contest you were looking for";
            return false;
        }

        if ($contest->submission_count >= 50) {
            $this->errors = "We're sorry but this contest has reached its submission limit";
            return false;
        }

        if ($contest->stop_time < date('Y-m-d H:i:s')) {
            $this->errors = "We're sorry but this contest has already ended";
            return false;
        }
        if (!$this->submission_library->userCanSubmit($this->ion_auth->user()->row()->id, $contest->id)) {
            $this->errors = "You have already submitted to this contest";
            return false;
        }

        $data = array(
            'owner'            => $this->ion_auth->user()->row()->id,
            'contest_id'       => $contest->id,
            'headline'         => $headline,
            'link_explanation' => $link_explanation,
            'text'             => $text,
            'attachment'       => null,
            'thumbnail_url'    => null,
        );
        if (!is_null($attachment)) {
            $data['attachment']    = $attachment . '.jpg';
            $data['thumbnail_url'] = $attachment . '_thumb.jpg';
        }

        $success = false;
        // Generate / validate fields based on the platform type
        if (!$this->userCanSubmit($data['owner'], $data['contest_id'])) {
            $this->error = 'You have already put in a submission for that contest';
            return false;
        }

        $company = $this->user->profile($contest->owner);
        if ($id = $this->submission->create($data)) {
            $email_data = array(
                'headline'         => $this->input->post('headline'),
                'text'             => $this->input->post('text'),
                'link_explanation' => $this->input->post('link_explanation'),
                'email'            => ($this->ion_auth->user() ? $this->ion_auth->user()->row()->email : false),
                'company'          => $company->name,
                'attachment_url'   => $attachment,
                'thumbnail_url'    => $data['thumbnail_url'],
                'eid'              => $this->mailer->id($this->ion_auth->user()->row()->email, 'submission_successful'),
            );

            $this->mailer->to($this->ion_auth->user()->row()->email)
                ->from('squad@tappyn.com')
                ->subject("Your submission was created!")
                ->html($this->load->view('emails/submission_success', $email_data, true))
                ->send();
            return $id;
        }
        return false;
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
        if ($check && $check->num_rows() > 0) {
            return false;
        }
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function update_submission($sid, $data)
    {
        return $this->db->where('id', $sid)->update('submissions', $data);
    }
}

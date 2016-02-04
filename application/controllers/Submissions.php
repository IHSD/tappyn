<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->view('templates/navbar');
        $this->load->model('submission');
        $this->load->model('contest');
    }

    /**
     * Get all submissions for a contest
     * @param  int $contest_id
     * @return void
     */
    public function index($contest_id)
    {
        $submissions = $this->contest->submissions($contest_id);
    }

    /**
     * Create a new submission
     * @return void
     */
    public function create($contest_id)
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->session->set_flashdata('error', 'You have to be logged in to create a submission');
            redirect("contests/show/{$contest_id}");
        }

        // Get the contest, and then dynamically change form validation rules, based on the type of the contest
        $contest = $this->contest->get($contest_id);
        if(!$contest)
        {
            $this->session->set_flashdata('error', 'You must be the owner to view all submissions');
            redirect("contests/show/{$contest_id}", 'refresh');
        }

        $this->data['contest'] = $contest;

        $data = array(
            'owner' => $this->ion_auth->user()->row()->id,
            'contest_id' => $contest->id
        );

        switch($contest->platform)
        {
            case 'facebook':
                $this->form_validation->set_rules('headline', 'Headline', 'required');
                $this->form_validation->set_rules('text', 'Text', 'required');
                $this->form_validation->set_rules('link_explanation', 'Link Explanation', '');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                    $data['headline'] = $this->input->post('headline');
                    $data['link_explanation'] = $this->input->post('llink_explanation');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission->create($data)))
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Headline'] = array(
                        'name' => 'headline',
                        'id' => 'headline',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('headline')
                    );
                    $fields['Text'] = array(
                        'name' => 'text',
                        'id' => 'text',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('text')
                    );
                    $fields['Link Explanation'] = array(
                        'name' => 'link_explanation',
                        'id' => 'link_explanation',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('link_explanation')
                    );

                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
                }
            break;
            case 'google':
                $this->form_validation->set_rules('headline', 'Headline', 'required');
                $this->form_validation->set_rules('description', "Description", 'required');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                    $data['description'] = $this->input->post('description');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission->create($data)))
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Headline'] = array(
                        'name' => 'headline',
                        'id' => 'headline',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('headline')
                    );
                    $fields['Description'] = array(
                        'name' => 'description',
                        'id' => 'description',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('description')
                    );
                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
                }
            break;
            case 'twitter':
                $this->form_validation->set_rules('text', 'Text', 'required');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission->create($data)))
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Text'] = array(
                        'name' => 'text',
                        'id' => 'text',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('text')
                    );
                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
                }
            break;
            case 'trending':

            break;
            case 'tagline':

            break;
            case 'general':
                $this->form_validation->set_rules('text', 'Text', 'required');
                if($this->form_validation->run() == true)
                {
                    $data['text'] = $this->input->post('text');
                }
                if($this->form_validation->run() == true && ($sid = $this->submission->create($data)))
                {
                    $this->session->set_flashdata('message', 'Your ad has successfully been submitted');
                    redirect("contests/show/{$contest_id}");
                }
                else
                {
                    $fields = array();
                    $fields['Text'] = array(
                        'name' => 'text',
                        'id' => 'text',
                        'type' => 'text',
                        'value' => $this->form_validation->set_value('text')
                    );
                    // Generate fields for the submission form;
                    $this->data['fields'] = $fields;
                }
            break;
        }

        $this->load->view('submissions/create', $this->data);
    }

    /**
     * Edit a submission
     * @return void
     */
    public function edit() {}

    /**
     * Remove a submission
     * @return void
     */
    public function delete() {}
}

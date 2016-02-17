<?php defined("BASEPATH") or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail(array(
                'error' => "You must be logged in to access this area"
            ))->code(401)->respond();
            return;
        }
        $this->load->model('user');
        $this->load->model('submission');
        $this->load->model('contest');
        $this->load->library('stripe/stripe_account_library');
        $this->stripe_account_id = $this->user->account($this->ion_auth->user()->row()->id);
    }

    /**
     * Generate a users dashboard.
     *
     * If its a company, we pull in all the contests.
     * Other wise we pull in a users submissions
     * @return void
     */
    public function dashboard()
    {
        $this->data['status'] = 'all';
        if($this->ion_auth->in_group(2))
        {
            // generate the user dashboard of submissions
            $submissions = $this->submission->getByUser($this->ion_auth->user()->row()->id , array());
            if($submissions !== FALSE)
            {
                $this->responder->data(
                    array(
                        'submissions' => $submissions
                    )
                )->respond();
            } else {
                $this->responder->fail(array(
                    'error' => 'There was an error fetching your dashboard'
                ))->code(400)->respond();
            }
        }
        else
        {
            $contests = $this->contest->fetchAll(array('owner' => $this->ion_auth->user()->row()->id));
            if($contests !== FALSE)
            {
                $this->responder->data(
                    array(
                        'contests' => $contests
                    )
                )->respond();
            } else {
                $this->responder->fail(array(
                    'error' => 'There was an error fetching your dashboard'
                ))->code(400)->respond();
            }
        }
    }

    public function in_progress()
    {
        $this->data['status'] = 'active';
        if($this->ion_auth->in_group(2))
        {
            $submissions = $this->submission->getActive($this->ion_auth->user()->row()->id , array());
            if($submissions !== FALSE)
            {
                $this->responder->data(
                    array(
                        'submissions' => $submissions
                    )
                )->respond();
            } else {
                $this->responder->fail(array(
                    'error' => 'There was an error fetching your dashboard'
                ))->code(400)->respond();
            }
        } else {
            $contests = $this->contest->fetchAll(array('owner' => $this->ion_auth->user()->row()->id, 'stop_time >' => date('Y-m-d H:i:s')));
            if($contests !== FALSE)
            {
                $this->responder->data(
                    array(
                        'contests' => $contests
                    )
                )->respond();
            } else {
                $this->responder->fail(array(
                    'error' => 'There was an error fetching your dashboard'
                ))->code(400)->respond();
            }
        }
    }

    public function completed()
    {
        $this->data['status'] = 'winning';
        if($this->ion_auth->in_group(2))
        {
            $submissions = $this->submission->getWinning($this->ion_auth->user()->row()->id);
            if($submissions !== FALSE)
            {
                $this->responder->data(
                    array(
                        'submissions' => $submissions
                    )
                )->respond();
            } else {
                $this->responder->fail(array(
                    'error' => 'There was an error fetching your dashboard'
                ))->code(400)->respond();
            }
        } else {
            $contests = $this->contest->fetchAll(array('owner' => $this->ion_auth->user()->row()->id, 'stop_time <' => date('Y-m-d H:i:s')));
            if($contests !== FALSE)
            {
                $this->responder->data(
                    array(
                        'contests' => $contests
                    )
                )->respond();
            } else {
                $this->responder->fail(array(
                    'error' => 'There was an error fetching your dashboard'
                ))->code(400)->respond();
            }
        }
    }

    /**
     * View a users profile
     * @return void
     * @todo Remove previous image on update of company_logo
     */
    public function profile()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if($this->ion_auth->in_group(2))
            {
                $data = array(
                    'age' => $this->input->post('age_range'),
                    'gender' => $this->input->post('gender'),
                    'state' => $this->input->post('state'),
                    'school' => $this->input->post('school')
                );

                if(!$this->user->saveProfile($this->ion_auth->user()->row()->id, $data))
                {
                    $this->responder
                        ->fail(array(
                            'error' => "There was an error updating your profile"
                        ))
                        ->code(500)
                        ->respond();
                    return;
                } else {
                    $this->responder
                        ->message(
                            'Profile was successfully updated'
                        )
                        ->data(array(
                            'profile' => $this->user->profile($this->ion_auth->user()->row()->id)
                        ))
                        ->respond();
                    return;
                }
            }
            else if($this->ion_auth->in_group(3))
            {
                $valid = true;
                $data = array(
                    'mission' => $this->input->post('mission'),
                    'extra_info' => $this->input->post('extra_info'),
                    'company_email' => $this->input->post('company_email'),
                    'company_url' => $this->input->post('company_url'),
                    'facebook_url' => $this->input->post('facebook_url'),
                    'name' => $this->input->post('name'),
                );
                if(isset($_FILES['logo_url']))
                {
                    $config['upload_path'] = APPPATH.'uploads/';
                    $config['allowed_types'] = 'git|jpg|jpeg|png';
                    $config['remove_spaces'] = true;
                    $config['encrypt_name'] = true;
                    $this->load->library('upload', $config);
                    if(!$this->upload->do_upload('logo_url'))
                    {
                        $this->responder
                            ->fail($this->upload->display_errors() ? $this->upload->display_errors() : array('error' => "There was an error uploading your image"))
                            ->code(500)
                            ->respond();
                        return;
                    } else {
                        $data['logo_url'] = $this->upload->data()['file_name'];
                    }
                }
                if($valid)
                {
                    if(!$this->user->saveProfile($this->ion_auth->user()->row()->id, $data))
                    {
                        $this->responder
                            ->fail(array('error' => "There was an error updating your profile"))
                            ->code(500)
                            ->respond();
                    } else {
                        $this->responder
                            ->message(
                                'Profile was successfully updated'
                            )
                            ->data(array(
                                'profile' => $this->user->profile($this->ion_auth->user()->row()->id)
                            ))
                            ->respond();
                        return;
                    }
                }
            }
        } else {
            $profile = $this->user->profile($this->ion_auth->user()->row()->id);
            $this->responder->data(array(
                'profile' => $profile
            ))->respond();
            return;
        }
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Companies extends CI_Controller
{
    protected $stripe_customer_id = false;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('company');
        $this->load->model('user');
        $this->load->model('contest');
        $this->config->load('secrets');
        $this->load->library('payout');
        $this->data['publishable_key'] = $this->config->item('stripe_api_publishable_key');
        $this->load->library('stripe/stripe_customer_library');
        if($this->ion_auth->logged_in()) $this->stripe_customer_id = $this->company->payment_details($this->ion_auth->user()->row()->id);
    }

    public function index($offset = 0)
    {
        $companies = $this->company->select('*')->from('profiles')->where(
            'summary IS NOT NULL', NULL
        )->limit(25, $offset);
        $followed = $this->input->get('followed');
        if($followed)
        {
            $follows = $this->user->following($this->ion_auth->user()->row()->id);
            if(empty($follows))
            {
                $this->responder->data(array())->respond();
                return;
            }
            $this->company->where_in('id', $follows);
        }
        $companies = $this->company->fetch()->result();
        $this->responder->data(array(
            'companies' => $companies
        ))->respond();
    }

    public function contests($cid)
    {
        $contests = array();
        $contests['active_contests'] = $this->db->select('*')->from('contests')->where(array(
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s'),
            'paid' => 1,
            'owner' => $cid
        ))->get()->result();

        $contests['completed_contests'] = $this->db->select('*')->from('contests')->where(array(
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time <' => date('Y-m-d H:i:s'),
            'paid' => 1,
            'owner' => $cid
        ))->get()->result();


        foreach($contests['active_contests'] as $result)
        {
            $result->submission_count = $this->contest->submissionsCount($result->id);
        }
        foreach($contests['completed_contests'] as $contest)
        {
            $submission = new StdClass();
            $payout = $this->db->select('*')->from('payouts')->where('contest_id', $contest->id)->limit(1)->get();
            if($payout->num_rows() == 1)
            {
                $submission = $this->submission->get($payout->row()->submission_id);
            }
            $contest->winner = $submission;
            $contest->submission_count = $this->contest->submissionsCount($contest->id);
        }

        $contests['pending_contests'] = $this->db->select('*')->from('contests')->where(array(
            'start_time >' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s'),
            'paid' => 1,
            'owner' => $cid
        ))->get()->result();
        $this->responder->data(array('contests' => $contests))->respond();
    }

    public function show($cid = 0)
    {
        if(!$this->ion_auth->in_group(3, $cid))
        {
            $this->responder->fail(
                "That company does not exist"
            )->code(500)->respond();
            return;
        }
        $company = $this->company->get($cid);
        unset($company->stripe_customer_id);
        if(!$company)
        {
            $this->responder->fail(
                "That company does not exist"
            )->code(500)->respond();
            return;
        }

        $company->requests = (int) $this->db->select('COUNT(*) as count')->from('requests')->where(array(
            'company_id' => $cid,
            'fulfilled' => 0
        ))->get()->row()->count;

        $company->follows = $this->db->select('COUNT(*) as count')->from('follows')->where('following', $company->id)->get()->row()->count;

        if($this->ion_auth->logged_in()){
            $uid = $this->ion_auth->user()->row()->id;
            $user_follow = $this->db->select('*')->from('follows')->where(array('follower' => $uid, 'following' => $cid))->get();
            $company->user_may_follow = TRUE;
            if($user_follow->num_rows() == 1)
            {
                $company->user_may_follow = FALSE;
            }
        }
        $this->responder->data(array(
            'company' => $company
        ))->respond();
    }

    public function request_contest($cid)
    {
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(2))
        {
            $this->responder->fail("Unauthorized Access")
                            ->code(403)
                            ->respond();
                            return;
        }

        $req_check = $this->company->select('*')->from('requests')->where(array(
            'user_id' => $this->ion_auth->user()->row()->id,
            'company_id' => $cid,
            'fulfilled' => 0
        ))->fetch();
        if(!$req_check || $req_check->num_rows() > 0)
        {
            $this->responder->fail("You've already requested a contest from them!")->code(500)->respond();
            return;
        }

        if($this->db->insert('requests', array(
            'company_id' => $cid,
            'user_id' => $this->ion_auth->user()->row()->id,
            'fulfilled' => 0,
            'requested_at' => time()
        )))
        {
            $this->responder->respond();
        } else {
            $this->responder->fail("There was an error making your request")->code(500)->respond();
        }
    }


    public function dashboard()
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if($this->ion_auth->in_group(2))
        {
            redirect("users/dashboard");
        }

        $this->data['status'] = 'all';

        if($this->input->get('type') === 'completed' || $this->input->get('type') === 'need_winner')
        {
            $this->contest->where('stop_time <',date('Y-m-d H:i:s'));
        }
        else if($this->input->get('type') === 'in_progress')
        {
            $this->contest->where(array(
                'start_time <' => date('Y-m-d H:i:s'),
                'stop_time >' => date('Y-m-d H:i:s'),
                'paid' => 1
            ));
        }

        // Make sure we only grab ones belonging to the user
        $this->contest->where('contests.owner', $this->ion_auth->user()->row()->id);
        $contests = $this->contest->fetch();
        if($contests !== FALSE)
        {
            $contests = $this->contest->result();
            // Check the input type
            if($this->input->get('type') === 'need_winner')
            {
                foreach($contests as $key => $contest)
                {
                    if($this->payout->exists(array('contest_id' => $contest->id)))
                    {
                        unset($contests[$key]);
                    }
                }
            }
            foreach($contests as $contest)
            {
                $contest->submission_count = $this->contest->submissionsCount($contest->id);
                $contest->views = $this->contest->views($contest->id);
                $contest->votes = $this->vote->select("COUNT(*) as count")->where('contest_id', $contest->id)->fetch()->row()->count;
                $share_data = $this->submission->select('SUM(shares) as shares, SUM(share_clicks) as share_clicks')->where('contest_id', $contest->id)->fetch()->row();
                $contest->shares = $share_data->shares;
                $contest->share_clicks = $share_data->share_clicks;
                /**
                 * Denote the status of the contest
                 */
                 $contest->status = 'active';

                 if($contest->paid == 0)
                 {
                     $contest->status = 'draft';
                 } else if($contest->stop_time < date('Y-m-d H:i:s')) {
                     if($this->payout->exists(array('contest_id' => $contest->id)))
                     {
                         $contest->status = 'completed';
                     } else {
                         $contest->status = 'needs_winner';
                     }
                 } else if($contest->start_time > date('Y-m-d H:i:s')) {
                     $contest->status = 'scheduled';
                 }
            }
            $this->responder->data(
                array(
                    'contests' => $contests
                )
            )->respond();
        } else {
            $this->responder->fail('There was an error fetching your dashboard')->code(500)->respond();
        }
    }

    public function profile()
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $data = array(
                'logo_url' => $this->input->post('logo_url'),
                'mission' => $this->input->post('mission'),
                'extra_info' => $this->input->post('extra_info'),
                'name' => $this->input->post('name'),
                'company_email' => $this->input->post('company_email'),
                'company_url' => $this->input->post('company_url'),
                'facebook_url' => $this->input->post('facebook_url'),
                'twitter_handle' => $this->input->post('twitter_handle'),
                'different' => $this->input->post('different'),
                'summary' => $this->input->post('summary')
            );
            if($this->user->saveProfile($this->ion_auth->user()->row()->id, $data))
            {
                $this->responder->data(array('profile' => $this->user->profile($this->ion_auth->user()->row()->id)))->message("Profile successfully updated")->respond();
            } else {
                $this->responder->fail("There was an error updating your profile")->code(500)->respond();
            }
        } else {
            $this->responder->data(array(
                'profile' => $this->user->profile($this->ion_auth->user()->row()->id)
            ))->respond();
        }
    }

    public function accounts()
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        // Check if we have to process a form in any way
        if($this->input->post('stripeToken') && $this->stripe_customer_id)
        {
            // We're going to add a new payment method to the customer
            if($this->stripe_customer_library->addPaymentSource($this->stripe_customer_id, $this->input->post('stripeToken')))
            {
                $this->data['message'] = "Your payment method was successfully added";
            } else {
                $this->data['error'] = ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : "An unknown error occured");
            }
        }
        else if($this->input->post('stripeToken') && $this->input->post('remember_me'))
        {
            // We're going to create a new customer altogether
            if($customer = $this->stripe_customer_library->create($this->ion_auth->user()->row()->id, $this->input->post('stripeToken')))
            {
                // Create row in database for the user
                $this->stripe_customer_id = $customer->id;
            } else {
                $this->data['error'] = $this->stripe_customer_library->errors();
            }
        }

        $this->data['customer'] = NULL;

        // After any proccessing, we fetch the updated customer to then show the user
        if($this->stripe_customer_id)
        {
            $this->data['customer'] = $this->stripe_customer_library->fetch($this->stripe_customer_id);
            $this->responder->data(array(
                'customer' => $this->data['customer']
            ))->respond();
        } else {
            $this->responder->fail("You dont have any account details yet")->code(200)->respond();
            return;
        }
    }

    public function removeCard()
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if(!$stripe_customer_id)
        {
            $this->session->set_flashdata('You havent created a payment method with us yet');
            redirect('companies/accounts', 'refresh');
        }
        if($this->input->post('source_id'))
        {
            $data = array(
                'default_source' => $this->input->post('source_id')
            );

            if($this->stripe_customer_library->update($stripe_customer_id,$data))
            {
                $this->session->set_flashdata('message', 'Default payment option successfully updated');
                redirect('companies/accounts', 'refresh');
            } else {
                $this->session->set_flashdata('error', ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : 'An unknown error occured'));
                redirect('companies/accounts', 'refresh');
            }
        } else {
            $this->session->set_flashdata('You must provide a payment option to remove');
            redirect('companies/accounts', 'refresh');
        }
    }

    /**
     * Generate the payment for a contest
     *
     * If the user has selected to be remembered, we create a customer and charge that
     * Else we just straight charge the card
     *
     * // $contest_id, $token = NULL, $customer_id = NULL, $source_id = NULL, $amount = 100
     * @param  [type] $contest_id [description]
     * @todo Still need to test selected payment method
     * @return [type]             [description]
     */
    public function payment($contest_id = FALSE)
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        // Initialize the control variables
        $charge = FALSE;
        $amount = 9999;

        if(!$contest_id)
        {
            $this->responder->fail("You must supply a contest")->code(500)->respond();
            return;
        }

        if(!$contest = $this->contest->get($contest_id))
        {
            $this->responder->fail("That contest doesnt exist silly")->code(500)->respond();
            return;
        }
        // Check that the contest has not aleady been paid for
        $check = $this->db->select('*')->from('stripe_charges')->where('contest_id', $contest_id)->get();
        if($check && $check->num_rows() > 0)
        {
            $this->responder->fail("That contest has already been paid for")->code(500)->respond();
            return;
        }

        // If there was a voucher supplied, lets run that.
        // If we no longer need a charge, we can skip all the stripe crap
        $this->load->library('vouchers_library');
        $this->load->library('stripe/stripe_charge_library');
        $this->load->library('stripe/stripe_customer_library');
        if($this->input->post('voucher_code'))
        {
            $voucher = $this->voucher->where('code', $this->input->post('voucher_code'))->limit(1)->fetch();
            // Make sure the voucher exists
            if(!$voucher || $voucher->num_rows() == 0)
            {
                $this->responder->fail("We couldnt find the voucher you supplied")->code(500)->respond();
                return;
            }
            $voucher = $voucher->row();
            if(!$this->vouchers_library->is_valid($voucher->id))
            {
                $this->responder->fail(($this->vouchers_library->errors() ? $this->vouchers_library->errors() : "An unknown error occured"))->code(500)->respond();
                return;
            }
            if(!$this->vouchers_library->redeem($voucher->id, $contest->id))
            {
                $this->responder->fail(($this->vouchers_library->errors() ? $this->vouchers_library->errors() : "An unknown error occured"))->code(500)->respond();
                return;
            }
            if($voucher->discount_type == 'amount')
            {
                $amount = $amount - ($voucher->value * 100);
            } else {
                $amount = $amount - ($amount * $voucher->value);
            }

        }
        if($amount > 000) {

            // If payment details were supplied, we're either going to charge the card, or create / update a customer
            if($this->input->post('stripe_token'))
            {
                if($this->input->post('save_method'))
                {
                    // Update the customer with the new payment method, and get the source id
                    if($this->stripe_customer_id)
                    {
                        $customer = $this->stripe_customer_library->update($this->stripe_customer_id, array("source" => $this->input->post('stripe_token')));
                        $charge = $this->stripe_charge_library->create($contest_id, NULL, $this->stripe_customer_id, NULL, 100);
                    }
                    // We need to create a customer, save the payment method, and charge them accordingly
                    else
                    {
                        // Create the customer
                        $customer = $this->stripe_customer_library->create($this->ion_auth->user()->row()->id, $this->input->post('stripe_token'), $this->ion_auth->user()->row()->email);
                        // Charge the customer_id
                        $charge = $this->stripe_charge_library->create($contest_id, NULL, $customer->id, NULL, 100);
                    }
                }
                // The user does not want to save the method, so we just charge the card
                else
                {
                    $charge = $this->stripe_charge_library->create($contest_id, $this->input->post('stripe_token'), NULL, NULL, 100);
                }
            }

            // Check if we have a customer, and chosen source
            else if($this->input->post('source_id') && $this->stripe_customer_id)
            {
                $charge = $this->stripe_charge_library->create($contest_id, NULL, $this->stripe_customer_id, $this->input->post('source_id'), 100);
            }
            // Tell them we cant process their request
            else
            {
                $this->responder->fail("We were unable to process your request")->code(500)->respond();
                return;
            }
        } else {
            $charge = array();
        }
        // Check if charge was succesful and handle accordingly
        if($charge !== FALSE)
        {
            $this->contest->update($contest_id, array('paid' => 1));

            $this->responder->message(
                "Your payment was successfully processed!"
            )->respond();
            $eid = $this->mailer->id($this->ion_auth->user()->row()->email, 'contest_create');
            $this->mailer->queue($this->ion_auth->user()->row()->email, $this->ion_auth->user()->row()->id, 'contest_receipt', 'contest', $contest->id);
            // $this->mailer->to($this->ion_auth->user()->row()->email)
            //              ->from('squad@tappyn.com')
            //              ->subject("Receipt for your launched contest")
            //              ->html($this->load->view('emails/contest_payment', array(
            //                  'company' => $this->ion_auth->profile('name'),
            //                  'contest' => $contest,
            //                  'eid' => $eid,
            //                  'charge' => $charge,
            //                  'voucher' => isset($voucher) ? $voucher : FALSE
            //              ), TRUE))
            //              ->send();

            // We find any users who have submitted to one of this companies previous contests,
            // anybody who is following this contest, and anybody who has followed this interest
            $cids = array();
            $uids = array();
            $industry = $contest->industry;
            $owner = $this->ion_auth->user()->row()->id;
            $contests = $this->db->select('*')->from('contests')->where('owner', $owner)->get();
            foreach($contests->result() as $contest)
            {
                $cids[] = $contest->id;
            }

            $submissions = $this->db->select('owner')->from('submissions')->where_in('contest_id', $cids)->group_by('owner')->get();
            foreach($submissions->result() as $submission)
            {
                $uids[] = $submission->owner;
            }

            // Get all followers
            // Get all users who follow this industry
            foreach($uids as $uid)
            {
                $this->notification->create($uid, 'new_contest_launched', 'contest', $contest_id);
            }

            return;
        }

        // An error occured, so respond as such
        else
        {
            $this->responder->fail(
                $this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : ($this->stripe_charge_library->errors() ? $this->stripe_charge_library->errors() : "An unknown error occured with payment")
            )->code(500)->respond();
            return;
        }
    }

    public function setAsDefault()
    {
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        if(!$stripe_customer_id)
        {
            $this->session->set_flashdata('You havent created a payment method with us yet');
            redirect('companies/accounts', 'refresh');
        }
    }
}

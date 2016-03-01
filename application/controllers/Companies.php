<?php defined("BASEPATH") or exit('No direct script access allowed');

class Companies extends CI_Controller
{
    protected $stripe_customer_id = false;

    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(3))
        {
            $this->responder->fail("You must be logged in as a company to access this area")->code(401)->respond();
            exit();
        }
        $this->load->model('company');
        $this->load->model('user');
        $this->load->model('contest');
        $this->config->load('secrets');
        $this->load->library('payout');
        $this->data['publishable_key'] = $this->config->item('stripe_api_publishable_key');
        $this->load->library('stripe/stripe_customer_library');
        $this->stripe_customer_id = $this->company->payment_details($this->ion_auth->user()->row()->id);
    }

    public function dashboard()
    {
        if($this->ion_auth->in_group(2))
        {
            redirect("users/dashboard");
        }

        $this->data['status'] = 'all';

        if($this->input->get('type') === 'completed')
        {
            $this->contest->where('stop_time <',date('Y-m-d H:i:s'));
        }
        else if($this->input->get('type') === 'in_progress')
        {
            $this->contest->where(array(
                'start_time <' => date('Y-m-d H:i:s'),
                'stop_time >' => date('Y-m-d H:i:s')
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
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $data = array(
                'logo_url' => $this->input->post('logo_url'),
                'mission' => $this->input->post('mission'),
                'extra_info' => $this->input->post('extra_info'),
                'name' => $this->input->post('name'),
                'company_email' => $this->input->post('company_email'),
                'company_url' => $this->input->post('facebook_url'),
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
            $this->responder->fail("You dont have any account details yet")->code(500)->respond();
            return;
        }
    }

    public function removeCard()
    {
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
     * // $contest_id, $token = NULL, $customer_id = NULL, $source_id = NULL, $amount = 9999
     * @param  [type] $contest_id [description]
     * @todo Still need to test selected payment method
     * @return [type]             [description]
     */
    public function payment($contest_id = FALSE)
    {
        $charge = FALSE;
        if(!$contest_id)
        {
            $this->responder->fail("You must supply a contest")->code(500)->respond();
            return;
        }

        // Check that the contest has not aleady been paid for
        $check = $this->db->select('*')->from('stripe_charges')->where('contest_id', $contest_id)->get();
        if($check && $check->num_rows() > 0)
        {
            $this->responder->fail("That contest has already been paid for")->code(500)->respond();
            return;
        }

        $this->load->library('stripe/stripe_charge_library');
        $this->load->library('stripe/stripe_customer_library');
        // If payment details were supplied, we're either going to charge the card, or create / update a customer
        if($this->input->post('stripe_token'))
        {
            if($this->input->post('save_method'))
            {
                // Update the customer with the new payment method, and get the source id
                if($this->stripe_customer_id)
                {
                    $customer = $this->stripe_customer_library->update($this->stripe_customer_id, array("source" => $this->input->post('stripe_token')));
                    $charge = $this->stripe_charge_library->create($contest_id, NULL, $this->stripe_customer_id, NULL, 9999);
                }
                // We need to create a customer, save the payment method, and charge them accordingly
                else
                {
                    // Create the customer
                    $customer = $this->stripe_customer_library->create($this->ion_auth->user()->row()->id, $this->input->post('stripe_token'), $this->ion_auth->user()->row()->email);
                    // Charge the customer_id
                    $charge = $this->stripe_charge_library->create($contest_id, NULL, $customer->id, NULL, 9999);
                }
            }
            // The user does not want to save the method, so we just charge the card
            else
            {
                $charge = $this->stripe_charge_library->create($contest_id, $this->input->post('stripe_token'), NULL, NULL, 9999);
            }
        }

        // Check if we have a customer, and chosen source
        else if($this->input->post('source_id') && $this->stripe_customer_id)
        {
            $charge = $this->stripe_charge_library->create($contest_id, NULL, $this->stripe_customer_id, $this->input->post('source_id'), 9999);
        }
        // Tell them we cant process their request
        else
        {
            $this->responder->fail("We were unable to process your request")->code(500)->respond();
            return;
        }

        // Check if charge was succesful and handle accordingly
        if($charge)
        {
            $this->contest->update($contest_id, array('paid' => 1));

            $this->responder->message(
                "Your payment was successfully processed!"
            )->respond();
            return;
        }

        // An error occured, so respond as such
        else
        {
            $this->responder->fail(array(
                ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : ($this->stripe_charge_library->errors() ? $this->stripe_charge_library->errors() : "An unknown error occured with payment"))
            ))->code(500)->respond();
            return;
        }
    }

    public function setAsDefault()
    {
        if(!$stripe_customer_id)
        {
            $this->session->set_flashdata('You havent created a payment method with us yet');
            redirect('companies/accounts', 'refresh');
        }
    }
}

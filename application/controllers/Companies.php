<?php defined("BASEPATH") or exit('No direct script access allowed');

class Companies extends CI_Controller
{
    protected $stripe_customer_id = false;

    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in() || !$this->ion_auth->in_group(3))
        {
            $this->session->set_flashdata('error', 'You must be a company to access this area');
            redirect('contests/index', 'refresh');
        }
        $this->load->view('templates/navbar');
        $this->load->model('company');
        $this->config->load('secrets');
        $this->data['publishable_key'] = $this->config->item('stripe_api_publishable_key');
        $this->load->library('stripe/stripe_customer_library');
        $this->stripe_customer_id = $this->company->payment_details($this->ion_auth->user()->row()->id);
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
        else if($this->input->post('stripeToken'))
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
        }
        $this->load->view('companies/accounts.php', $this->data);
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
     * @return [type]             [description]
     */
    public function payment($contest_id = FALSE)
    {
        $charge = FALSE:;
        if(!$contest_id)
        {
            $this->responder->fail(array(
                'error' => "You must supply a contest"
            ))->code(400)->respond();
        }

        if($this->input->post('stripeToken'))
        {
            // Has the user entered new credit card details
            if($this->input->post('save_method'))
            {
                if($this->stripe_customer_id)
                {
                    // The company is already a customer, so we're gonna go ahead and add the payment method
                    $customer = $this->stripe_customer_library->update();
                } else {
                    // We're going to create a new customer on behalf of your company
                    $customer = $this->stripe_customer_library->create(
                            $this->ion_auth->user()->row()->id,
                            $this->input->post('stripeToken'),
                            $this->ion_auth->user()->row()->email
                    );
                    if(!$customer)
                    {
                        $this->responder->fail(array(
                            'error' => $this->stripe_customer_library->errors()
                        ))->code(500)->respond();
                    }
                }
                // Now we charge the customer
                /*
                    FIGURE OUT THE SOURCE ID DEBACLE
                 */
                $charge = $this->stripe_charge_library->create($contest_id, NULL, $customer->id, NULL, 9999)
            } else {
                // We don't want to save our payment method, so just straight charge it
                $charge = $this->stripe_charge_library->create($contest_id, $this->input->post('stripeToken'), NULL, NULL, 9999)
            }
        } else if($this->input->post('source_id') && $this->stripe_customer_id) {
            // Company is already a customer, and wants to used a saved payment source
            $charge = $this->stripe_transfer_library->create($contest_id, NULL, $this->stripe_customer_id, $this->input->post('source_id'), 9999);
        } else {
            $this->responder->fail(array(
                'error' => 'We were unable to process your request'
            ))->code(500)->respond();
        }

        if($charge)
        {
            // Update our contest as having been paid for,
            // and let the company know they are all set
            $this->contest->update($id, array('paid' => 1));
            
        } else {
            $this->responder->fail(array(
                ($this->stripe_customer_library->errors() ? $this->stripe_customer_library->errors() : ($this->stripe_charge_library->errors() ? $this->stripe_charge_library->errors() : array('error' => "An unknown error occured")))
            ))->code(500)->respond();
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

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Accounts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->session->set_flashdata('error', 'You have to be logged in to access this area');
            redirect('contests/index', 'refresh');
        }

        if(!$this->ion_auth->in_group(2))
        {
            $this->session->set_flashdata('error', 'Only content creators can access the accounts panel');
            redirect('contests/index', 'refresh');
        }
        $this->load->model('user');
        $this->load->library('stripe/stripe_account_library');
        $this->stripe_account_id = $this->user->account($this->ion_auth->user()->row()->id);
        if($this->stripe_account_id)
        {
            $this->account = $this->stripe_account_library->get($this->stripe_account_id);
        }

        $this->load->view('templates/navbar');
    }

    function accept_terms()
    {
        if(isset($_POST['stripe_tos'])) return true;
        $this->form_validation->set_message('stripe_tos', "To continue, you must accept Stripe's Terms of Service");
    }
    /**
     * Endpoint for setting user level account details
     * @return void
     */
    public function details()
    {
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');
        $this->form_validation->set_rules('dob_day', 'DOB - Day', 'required');
        $this->form_validation->set_rules('dob_month', 'DOB - Month', 'required');
        $this->form_validation->set_rules('dob_year', 'DOB - Year', 'required');
        $this->form_validation->set_rules('country', 'Country', 'required');
        $this->form_validation->set_rules('stripe_tos', "Terms of Service", 'callback_accept_terms');
        if($this->form_validation->run() === TRUE)
        {
            // Preproces
            $data = array(
                'legal_entity.type' => 'individual',
                'legal_entity.first_name' => $this->input->post('first_name'),
                'legal_entity.last_name' => $this->input->post('last_name'),
                'legal_entity.dob.day' => $this->input->post('dob_day'),
                'legal_entity.dob.year' => $this->input->post('dob_year'),
                'legal_entity.dob.month' => $this->input->post('dob_month'),
                'tos_acceptance.ip' => $_SERVER['REMOTE_ADDR'],
                'tos_acceptance.date' => time(),
                'country' => $this->input->post('country'),
                'currency' => 'USD'
            );
        }
        // If the form pass validation, and we can create / upadte based on presence
        if($this->form_validation->run() === TRUE &&
            ($this->stripe_account_id ?
                $this->stripe_account_library->update($this->stripe_account_id, $data) :
                $this->stripe_account_library->create($this->ion_auth->user()->row()->email, $data)))
        {
            // We have successfully created our account
            $this->session->set_flashdata('message', "Account details successfully updated");
            redirect('accounts/payment_methods', 'refresh');
        }
        $this->data['error'] = (validation_errors() ? validation_errors() : ($this->stripe_account_library->errors() ? $this->stripe_account_library->errors() : false));
        $this->load->view('users/accounts/details', $this->data);
    }

    /**
     * Endpoint for managing payment methods
     * @return void
     */
    public function payment_methods()
    {
        if(!$this->account)
        {
            $this->session->set_flashdata('error', 'You need to set your account details first');
            redirect('accounts/details', 'refresh');
        }
        if(!$this->account->transfers_enabled)
        {
            // Check that verification fields is not empty,
            // and that the only required field is the external account
            // else send back to details
            $fields = $this->account->verification->fields_needed;
            $key = array_search('external_account', $fields);
            unset($fields[$key]);
            if(!empty($fields))
            {
                $this->session->set_flashdata('error', 'You still have some account details to fill out! ('.implode(',', $fields).')');
                redirect('accounts/details', 'refresh');
            }
        }

        if($this->input->post('stripeToken'))
        {
            if($this->stripe_account_library->addSource($this->stripe_account_id, $this->input->post('stripeToken'), $this->input->post('currency')))
            {
                $this->data['message'] = "Account successfully updated";
            }
        }
        $this->data['error'] = ($this->stripe_account_library->errors() ? $this->stripe_account_library->errors() : false);
        $this->data['account'] = $this->stripe_account_library->get($this->stripe_account_id);
        $this->load->view('users/accounts/payment_methods', $this->data);
        // They have either set all of their data, or only need to create a new payment method
    }

    /**
     * Remove payment methods
     * @return void
     */
    public function remove_method()
    {
        if($this->input->post('source_id'))
        {
            foreach($this->account->external_accounts->data as $source)
            {
                if($source->id === $this->input->post('source_id'))
                {
                    if($this->stripe_account_library->removeSource($this->stripe_account_id, $source->id))
                    {
                        $this->session->set_flashdata('message', 'Payment method successfully removed');
                    } else {
                        $this->session->set_flashdata('error', $this->stripe_account_library->errors() ? $this->stripe_account_library->errors() : "An unknown error occured");
                    }
                }
            }
        } else {
            $this->session->set_flashdata('error', "You did not provide a payment method to remove");
        }
        redirect('accounts/payment_methods', 'refresh');
    }

    /**
     * Set default payment method
     * @return void
     */
    public function default_method()
    {

    }

    public function payouts()
    {
        $payouts = $this->db->select('*')->from('payouts')->where('user_id', $this->ion_auth->user()->row()->id)->get()->result();
        $this->load->view('users/accounts/payouts', array('payouts' => $payouts));
    }
}

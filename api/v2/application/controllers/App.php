<?php defined("BASEPATH") or exit('No direct script access allowed');

class App extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function contact_us()
    {
        $this->load->model('contact');

        $this->form_validation->set_rules('contact', 'Customer Type', 'required');
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('details', 'Message', 'required');

        if($this->form_validation->run() === TRUE)
        {
            $contact = new Contact();
            $data = array(
                ContactFields::EMAIL => $this->input->post('email'),
                ContactFields::CUSTOMER => $this->input->post('contact'),
                ContactFields::MESSAGE => $this->input->post('details')
            );
            if($contact->save())
            {
                Hook::trigger('new_contact_received', array('id' => $contact->id, 'email' => $contact->email));
            }
            else
            {
                $this->response->fail($contact->errors() ? $contact->errors() : "An unkown error occured")->code(500);
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

    public function mailing_list()
    {
        $this->load->model('subscriber');
        $sub = new Subscriber();

        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[mailing_list.email]|valid_email');
        if($this->form_validation->run() === true)
        {
            $sub->setData(array(SubscriberFields::EMAIL => $this->input->post('email')))
            if($sub->save())
            {
                $this->response->message(
                "You have successfully been added to our mailing list"
                )->respond();
                Hook::trigger('new_mail_subscriber', array('email' => $this->input->post('email')));
                return;
            }
            else
            {
                $this->response->fail($sub->errors() ? $sub->errors() : "An unknown error occured")->code(500)->respond();
                return;
            }
        }
        $this->response->fail((validation_errors() ? validation_errors() : ($sub->errors() ? $sub->errors() : "There was an error adding you to our mailing list")))->code(500)->respond();

    }

    public function unsubscribe()
    {

    }
}

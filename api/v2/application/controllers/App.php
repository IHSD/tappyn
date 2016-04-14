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
		$this->form_validation->set_rules('email', 'Email Address', 'required');
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

    $this->load->library("mailer");
    $this->load->model('contact');
    $this->form_validation->set_rules('contact', 'Customer Type', 'required');
    $this->form_validation->set_rules('email', 'Email Address', 'required');
    $this->form_validation->set_rules('details', 'Message', 'required');
    if($this->form_validation->run() == true)
    {
        // Pre process if necessaary
        $customer = $this->input->post('contact');
        $email = $this->input->post('email');
        $message = $this->input->post('details');
    }
    if($this->form_validation->run() == true && $this->contact->create($customer, $email, $message))
    {
        $this->mailer
            ->to('squad@tappyn.com')
            ->from($email)
            ->subject("New Contact Message Received")
            ->html($this->load->view('emails/contact_success', array('contact' => $customer, 'email' => $email, 'details' => $message), true))
            ->send();
        $this->responder->message(
            "Thank you for your message. We will contact you as soon as we can!"
        )->respond();
    } else {
        $this->responder->fail("There was an error submitting your contact request. Please try again later")->code(500)->respond();
    }
    public function mailing_list()
    {
        $this->load->model('subscriber');
        $sub = new Subscriber();

        $this->form_validation->set_rules('email', 'Email', 'required|is_unique[mailing_list.email]');
        if($this->form_validation->run() === true)
        {
            $sub->setData(array(SubscriberFields::EMAIL => $this->input->post('email')))
            if($sub->save())
            {
                $this->responder->message(
                "You have successfully been added to our mailing list"
                )->respond();
                Hook::trigger('new_mail_subscriber', array('email' => $this->input->post('email')));
                return;
            }
        }
        $this->responder->fail((validation_errors() ? validation_errors() : ($sub->errors() ? $sub->errors() : "There was an error adding you to our mailing list")))->code(500)->respond();

    }

    public function unsubscribe()
    {

    }
}

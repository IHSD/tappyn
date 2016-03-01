<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('contest');
	}

	public function unsubscribe()
	{
		if($this->db->where('email', $this->input->get('email'))->delete('mailing_list'))
		{
			$this->session->set_flashdata('message', 'You have successfully been removed form our mailing list');
		} else {
			$this->session->set_flashdata('error', 'There was an error removing you from our mailing list');
		}
		redirect('contests/index', 'refresh');
	}

	public function mailing_list()
	{
		$this->load->model('contact');
		$this->form_validation->set_rules('email', 'Email', 'required|is_unique[mailing_list.email]');
		if($this->form_validation->run() === true)
		{
			// Pre process
			$email = $this->input->post('email');
		}
		if($this->form_validation->run() === true && $this->contact->addToMailing($email))
		{
			$this->responder->message(
				"You have successfully been added to our mailing list"
			)->respond();
		} else {
			$this->responder->fail(($this->contact->errors() ? $this->contact->errors() : "There was an error adding you to our mailing list"))->code(500)->respond();
		}
	}

	/**have su
	 * Contact Us
	 * @return void
	 */
	public function contact_us()
	{
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
	}
}

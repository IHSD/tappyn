<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->view('templates/navbar');
		$this->load->model('contest');
	}

	public function index()
	{
		$this->data['contests'] = $this->contest->fetchAll(array(), 'start_time', 'desc', 10);
		$this->load->view('home/index', $this->data);
	}

	/**
	 * FAQ
	 * @return void
	 */
	public function faq()
	{
		$this->load->view('home/faq');
	}

	/**
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
			$this->data['message'] = 'Your request for contact has been submitted';
			$this->mailer
				->to('squad@tappyn.com')
				->from('Contact@tappyn.com')
				->subject("New Contact Message Received")
				->setHtml($this->load->view('emails/contact_success', array('contact' => $customer, 'email' => $email, 'details' = $message)))
				->send();
		} else {
			$this->data['error'] = (validation_errors() ? validation_errors() : ($this->contact->errors() ? $this->contact->errors() : false));
		}
		$this->load->view('home/contact_us', $this->data);
	}

	/**
	 * Privacy Policy
	 * @return void
	 */
	public function privacy_policy()
	{
		$this->load->view('home/privacy_policy');
	}

	/**
	 * Terms of Service Agreement
	 * @return void
	 */
	public function tos()
	{
		$this->load->view('home/tos');
	}

	/**
	 * How It Works Page
	 * @return [type] [description]
	 */
	public function how_it_works()
	{
		$this->load->view('home/how_it_works');
	}
}

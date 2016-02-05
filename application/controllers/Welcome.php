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
		$this->load->model('contact');
		$this->form_validation->set_rules('customer_type', 'Customer Type', 'required');
		$this->form_validation->set_rules('email', 'Email Address', 'required');
		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('topic', 'Topic', 'required');
		$this->form_validation->set_rules('message', 'Message', 'required');
		if($this->form_validation->run() === true)
		{
			// Pre process if necessaary
		}
		if($this->form_validation->run() === true && $this->contact->create($customer, $email, $subject, $topic, $message)))
		{
			$this->data['message'] = 'Your request for contact has been submitted';
		} else {
			$this->data['error'] = (validation_errors() ? validation_errors() : ($this->contact->errors() ? $this->contact->errors() : 'An unknown error occured'));
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

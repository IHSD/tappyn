<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->view('templates/navbar');
	}
	
	public function index() 		{ $this->load->view('home/index'); }

	public function faq() 			{ $this->load->view('home/faq'); }

	public function contact_us() 	{ $this->load->view('home/contact_us'); }

	public function privacy_policy(){ $this->load->view('home/privacy_policy'); }

	public function tos() 			{ $this->load->view('home/tos'); }

	public function how_it_works() 	{ $this->load->view('home/how_it_works'); }
}

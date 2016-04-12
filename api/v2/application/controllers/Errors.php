<?php defined("BASEPATH") or exit('No direct script access allowed');

class Errors extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('response');
    }

    public function show_404()
    {
        $this->response->fail('Not Found')->code(404)->respond();
    }

    public function show_403()
    {
        $this->response->fail('Unauthorized')->code(403)->respond();
    }

    public function invalid_route()
    {
        $this->response->fail('Invalid route')->code(404)->respond();
    }
}

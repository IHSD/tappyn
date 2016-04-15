<?php defined("BASEPATH") or exit('No direct script access allowed');

class Errors extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function no_route()
    {
        echo json_encode(array(
            'success' => FALSE,
            'code' => 404,
            'error' => "No route specified"
        ));
    }

    public function show_500()
    {
        echo json_encode(array(
            'success' => FALSE,
            'code' => 500,
            'error' => "A server error occured"
        ));
    }

    public function show_404()
    {
        echo json_encode(array(
            'success' => FALSE,
            'code' => 404,
            'error' => "Entity not found"
        ));
    }
}

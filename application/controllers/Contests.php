<?php defined("BASEPATH") or exit('No direct script access allowed');

class Contests extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('contest');
    }

    /**
     * View all available contests
     * @return void
     */
    public function index() {}

    /**
     * Fetch a single contest
     * @param  integer $id
     * @return void
     */
    public function show($id)
    {
        $contest = $this->contest->get($id);
        print_r($contest);
    }

    /**
     * Create a new contest, or render the creation form
     * @return void
     */
    public function create() {}

    /**
     * Edit and update a contest
     * @return void
     */
    public function edit() {}
}

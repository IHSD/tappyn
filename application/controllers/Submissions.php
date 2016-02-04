<?php defined("BASEPATH") or exit('No direct script access allowed');

class Submissions extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all submissions for a contest
     * @param  int $contest_id
     * @return void
     */
    public function index($contest_id) {}

    /**
     * Create a new submission
     * @return void
     */
    public function create() {}

    /**
     * Edit a submission
     * @return void
     */
    public function edit() {}

    /**
     * Remove a submission
     * @return void
     */
    public function delete() {}
}

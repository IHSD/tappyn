<?php defined("BASEPATH") or exit('No direct script access allowed');

class Follow extends MY_Model
{
    public $table = 'follows';

    public function __construct()
    {
        parent::__construct();
    }
}

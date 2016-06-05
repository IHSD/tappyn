<?php defined("BASEPATH") or exit('No direct script access allowed');

// Add to header of your file
use FacebookAds\Object\Ad;

// Add after echo "You are logged in "

// Initialize a new Session and instantiate an Api object

// Add to header of your file

class Test extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('interest');
        $this->interest->setDatabase($this->db);
    }

    public function testarooni($id)
    {
        $this->load->model('contest');
        $this->contest->get($id);
        var_dump($this->contest->_data);
    }

    public function path()
    {
        $start_time = (new \DateTime(""))->format(DateTime::ISO8601);
        $end_time = (new \DateTime("+1 day"))->modify("+1 seconds")->format(DateTime::ISO8601);
        var_dump($start_time, $end_time, __DIR__);
    }

    public function ad()
    {
        $this->load->library('ad_lib');
        $this->ad_lib->graph_ctr();
        $this->ad_lib->check_unsend();
    }
    public function index()
    {

    }

    // public function fetch()
    // {
    //     if($this->interest->create('asdfasaadfasdasdf', "asdfaasdfaasdfdfaasdf", 12))
    //     {
    //
    //     } else {
    //
    //     }
    //     redirect('test/tree', 'refresh');
    // }
    //
    // public function reset()
    // {
    //     $this->db->query('DELETE FROM interests; ALTER TABLE interests AUTO_INCREMENT = 1');
    // }
    //
    // public function delete($id)
    // {
    //     if($this->interest->delete($id))
    //     {
    //
    //     } else {
    //
    //     }
    //     redirect('test/tree', 'refresh');
    // }
    //
    // public function tree()
    // {
    //     echo json_encode($this->interest->tree());
    // }
    //
    // public function auth()
    // {
    //     var_dump($this->config->item('email_activation', 'ion_auth'));
    //     $this->config->set_item('email_activation', FALSE);
    //     var_dump($this->config->item('email_activation'));
    // }
}

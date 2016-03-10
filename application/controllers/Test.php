<?php defined("BASEPATH") or exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('interests');
        $this->interests->setDatabase($this->db);
    }

    public function fetch()
    {
        if($this->interests->create('asdfasaadfasdasdf', "asdfaasdfaasdfdfaasdf", 12))
        {

        } else {

        }
        redirect('test/tree', 'refresh');
    }

    public function reset()
    {
        $this->db->query('DELETE FROM interests; ALTER TABLE interests AUTO_INCREMENT = 1');
    }

    public function delete($id)
    {
        if($this->interests->delete($id))
        {

        } else {

        }
        redirect('test/tree', 'refresh');
    }

    public function tree()
    {
        echo json_encode($this->interests->tree());
    }
}

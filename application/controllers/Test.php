<?php defined("BASEPATH") or exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('interest');
        $this->interest->setDatabase($this->db);
    }

    public function fetch()
    {
        if($this->interest->create('asdfasaadfasdasdf', "asdfaasdfaasdfdfaasdf", 12))
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
        if($this->interest->delete($id))
        {

        } else {

        }
        redirect('test/tree', 'refresh');
    }

    public function tree()
    {
        echo json_encode($this->interest->tree());
    }

    public function auth()
    {
        var_dump($this->config->item('email_activation', 'ion_auth'));
        $this->config->set_item('email_activation', FALSE);
        var_dump($this->config->item('email_activation'));
    }
}

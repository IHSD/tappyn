<?php defined("BASEPATH") or exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('interest');
        $this->interest->setDatabase($this->db);
    }

    public function pcp()
    {
        $faker = Faker\Factory::create();
        $package_data = array(
            'platform' => 'facebook',
            'objective' => 'website_clicks',
            'headline' => $faker->text(50),
            'text' => $faker->text(200)
        );
        $this->mailer
             ->to($this->ion_auth->user()->row()->email)
             ->from("squad@tappyn.com")
             ->subject("Here's your post contest package!")
             ->html($this->load->view('emails/post_contest_package', $package_data, TRUE))
             ->send();
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

    function parse_objective()
    {

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

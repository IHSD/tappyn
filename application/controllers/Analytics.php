<?php defined("BASEPATH") or exit('No direct script access allowed');

class Analytics extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function open()
    {
        // Get and return our image file
        $name = FCPATH.'public/img/TappynLogo2.png';

        $fp = fopen($name, 'rb');
        header("Content-Type: image/png");
        header("Content-Length: " .filesize($name));
        fpassthru($fp);

        // Track the open
        $this->db->where('id', $this->input->get('eid'))
                 ->set('opened', 'opened + 1', FALSE)
                 ->update('mailing_queue');
    }

    public function click()
    {
        $this->db->where('id', $this->input->get('eid'))
                 ->set('clicks', 'clicks + 1', FALSE)
                 ->update('mailing_queue');
        header("Location: ".base_url($this->input->get('redirect')));
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Amazon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('s3');
        $this->load->library('image');
    }

    public function test($filename)
    {
        $hash = hash('sha256', uniqid());
        echo "FILESIZE :: ".filesize("/home/rob_wittman/Pictures/".$filename)."\n";
        $image_data = 'data:image/png;base64,'.base64_encode(file_get_contents("/home/rob_wittman/Pictures/".$filename));
        $this->s3->upload($image_data, $hash);
        $thumb = $this->image->compress($image_data);
        $this->s3->upload($thumb, $hash.'_thumb');
    }

    public function connect()
    {
        $this->responder->data(array('access_token' => $this->s3->connect()))->respond();
    }
}

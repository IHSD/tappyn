<?php defined("BASEPATH") or exit('No direct script access allowed');

class Amazon extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('s3');
        $this->load->library('image');
    }

    public function connect()
    {
        $this->responder->data(array('access_token' => $this->s3->connect()))->respond();
    }

    public function watermark($sid)
    {
        $submission = $this->db->select('*')->from('submissions')->where('id', $sid)->get()->row();

        $im = imagecreatefromstring(file_get_contents($submission->attachment));
        $watermark = imagecreatefrompng(base_url().'public/img/watermark.png');

        // Set some necessary variables for the image
        $ix = imagesx($im);
        $iy = imagesy($im);
        $wx = imagesx($watermark);
        $wy = imagesy($watermark);

        // Set variables for imagecopyresized
        $dst_x = 0;         // Starting X position on destination
        $dst_y = 0;         // Starting Y position on destination
        $dst_w = $ix;         // Height of destination paste area
        $dst_h = $iy;         // Width of destination paste area

        // The ones below should never change. We always want all of our watermark
        $src_x = 0;         // Starting X on source image
        $src_y = 0;         // Starting Y on source image
        $src_w = $wx;         // Height of source image to copy
        $src_h = $wy;         // Width of source image to copy

        error_log("Height of image      :: {$iy}");
        error_log("Width of image       :: {$ix}");
        error_log("Height of watermark  :: {$wy}");
        error_log("Width of watermark   :: {$wx}");

        if($ix >= $iy)
        {
            // We know we're scaling to the Y
            $dst_y = 0;
            $dst_h = $iy;

            // Out image is wider than it is tall
            // We need to find the starting point and width for the X-Axis
            $dst_w = $iy;
            $dst_x = ($ix/2 - $iy/2);
        }
        else
        {
            // We need to do the inverse for the Y-Axis
            $dst_x = 0;
            $dst_w = $ix;

            $dst_h = $ix;
            $dst_y = ($iy/2 - $ix/2);
        }


        imagecopyresized($im, $watermark, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

        header('Content-type: image/jpeg');
        imagejpeg($im);
        // $im = imagecreatefromstring(file_get_contents('https://tappyn.s3.amazonaws.com/074d532655185d12298cc9a5754f277e592c620f5800621edb113317fc068b35.jpg'));
        // $wx = imagesx($im)/2 - imagesx($watermark)/2;
        // $wy = imagesy($im)/2 - imagesy($watermark)/2;
        // imagecopy($im, $watermark, $wx, $wy, 0,0, imagesx($watermark), imagesy($watermark));
        // imagejpeg($im);
        // $hash = hash('sha256', uniqid());
        // $image_data = 'data:image/png;base64,'.base64_encode(file_get_contents("/home/rob_wittman/Pictures/".$filename));
        // $watermark = $this->image->create_watermark($image_data);
        // $this->s3->upload($watermark, $hash.'_watermark.jpg');
    }
}

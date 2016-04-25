<?php defined("BASEPATH") or exit('No direct script access allowed');

class Image
{
    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function upload($upload, $filename)
    {
        if($this->s3->upload($upload, $filename))
        {
            return TRUE;
        }
        return FALSE;
    }

    public function compress($base64_data)
    {
        $str="data:image/png;base64,";
        $data = str_replace($str, '', $base64_data);
        $data = base64_decode($data);
        file_put_contents("/var/www/tappyn/test.jpg", $data);
    }

    public function create_thumbnail()
    {

    }
}

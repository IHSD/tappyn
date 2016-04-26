<?php defined("BASEPATH") or exit('No direct script access allowed');

class Image
{
    protected $options = array(
        'height' => 150,
        'width' => 150,
        'file_type' => 'jpeg',
        'mime_type' => "image/jpeg"
    );

    protected $mime_types = array(
        'data:image/jpg;base64,',
        'data:image/gif;base64,',
        'data:image/png;base64,',
        'data:image/gif;base64,'
    );

    public function __construct()
    {

    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function setOpts($key, $val = NULL)
    {
        if(is_array($key))
        {
            foreach($key as $k => $v)
            {
                $this->setOpts($k, $v);
            }
        }
        $this->options[$key] = $val;
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
        $filename = hash('sha256', uniqid());
        $tmpfile = APPPATH.'uploads/'.$filename;
        error_log($base64_data);
        if(strpos($base64_data, ',') !== FALSE) $base64_data = explode(',', $base64_data)[1];

        $im = imagecreatefromstring(base64_decode($base64_data));

        $ox = imagesx($im);
        $oy = imagesy($im);

        $nx = 600;
        $ny = 300;

        $nm = imagecreatetruecolor($nx, $ny);

        imagecopyresized($nm, $im, 0,0,0,0, $nx, $ny, $ox, $oy);

        // Catch our image in buffer
        ob_start();
        imagejpeg($nm);
        $imageContents = ob_get_contents();
        ob_end_clean();
        error_log(base64_encode($imageContents));
        $base64_image_data = base64_encode($imageContents);

        imagedestroy($im);
        return 'data:image/jpeg;base64,'.$base64_image_data;
    }

    public function create_thumbnail()
    {

    }

    public function option($key)
    {
        if(!array_key_exists($key, $this->options) || $this->options[$key] == FALSE || is_null($this->options[$key]))
        {
            return FALSE;
        }
        return $this->options[$key];
    }
}

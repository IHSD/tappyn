<?php defined("BASEPATH") or exit('No direct script access allowed');

use Aws\S3\S3Client;

class S3
{
    private $bucket_name;
    private $aws_access_key_id;
    private $aws_secret_key;
    private $s3;
    public function __construct()
    {
        $this->load->config('secrets');
        $this->aws_access_key_id = $this->config->item('aws_access_key_id');
        $this->aws_secret_key  = $this->config->item('aws_secret_key');
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => 'us-west-2',
            'credentials' => [
                'key' => $this->aws_access_key_id,
                'secret' => $this->aws_secret_key
            ]
        ]);
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function test()
    {
        try {
            $result = $this->s3->putObject([
                'Bucket' => 'tappyn',
                'Key'    => 'testPhoto.png',
                'Body'   => fopen($this->input->post('photo'), 'r'),
                'ACL'    => "public-read"
            ]);
        } catch(Aws\Exception\S3Exception $e) {
            die("There was an error uploading the file. ".$e->getMessage());
        }
        echo "File upload successful";
        error_log(json_encode($result));
    }

    public function upload($upload, $filename)
    {
        try {
            $result = $this->s3->putObject([
                'Bucket' => 'tappyn',
                'Key'    => $filename,
                'Body'   => fopen($upload, 'r'),
                'ACL'    => "public-read"
            ]);
        } catch(Aws\Exception\S3Exception $e) {
            $this->errors = $e->getMessage();
            return FALSE;
        }
        error_log(json_encode($result));
        return TRUE;
    }

    public function connect()
    {
        $this->bucket_name = $this->input->post('bucket');
        $now = time() + (12 * 60 * 60 * 1000);
        $expire = gmdate('Y-m-d\TH:i:s\Z', $now);

        $url = 'https://' . $this->bucket_name . '.s3.amazonaws.com';
        $policy_document = '
            {"expiration": "' . $expire . '",
             "conditions": [
                {"bucket": "' . $this->bucket_name . '"},
                ["starts-with", "$key", ""],
                {"acl": "public-read"},
                ["content-length-range", 0, 20971520],
                ["starts-with", "$Content-Type", ""]
            ]
        }';

        $policy = base64_encode($policy_document);

        $hash = $this->hmacsha1($this->aws_secret_key, $policy);

        $signature = $this->hex2b64($hash);

        $token = array('policy' => $policy,
                       'signature' => $signature,
                       'key' => $this->aws_access_key_id);

        return $token;
    }

    private function setBucketName($bucket_name)
    {
        $this->bucket_name = $bucket_name;
    }

    private function hmacsha1($key, $data)
    {
       $blocksize = 64;
       $hashfunc = 'sha1';
       if(strlen($key) > $blocksize)
           $key = pack('H*', $hashfunc($key));
       $key = str_pad($key, $blocksize, chr(0x00));
       $ipad = str_repeat(chr(0x36), $blocksize);
       $opad = str_repeat(chr(0x5c), $blocksize);
       $hmac = pack('H*', $hashfunc(($key ^ $opad).pack('H*', $hashfunc(($key ^ $ipad).$data))));
       return bin2hex($hmac);
    }

    private function hex2b64($str) {
        $raw = '';
        for($i=0; $i < strlen($str); $i+=2) {
            $raw .= chr(hexdec(substr($str, $i, 2)));
        }
        return base64_encode($raw);
    }
}

<?php defined("BASEPATH") or exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        $this->load->view('templates/navbar');
        parent::__construct();
    }

    public function paypal()
    {
        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AYSq3RDGsmBLJE-otTkBtM-jBRd1TCQwFf9RGfwddNXWz0uFU9ztymylOhRS',     // ClientID
                'EGnHDxD_qRPdaLdZz8iCr8N7_MzF-YHPTkjs6NKYQvQSBngp4PTTVWkPZRbL'      // ClientSecret
            )
        );
    }

    public function stripe()
    {
        \Stripe\Stripe::setApiKey("sk_test_BQokikJOvBiI2HlWgH4olfQ2");
    }
}

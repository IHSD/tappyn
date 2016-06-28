<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use Maknz\Slack\Client;

class slack
{
    private $client;
    public function __construct()
    {
        // setting from https://my.slack.com/services/new/incoming-webhook
        $this->client = new Client('https://hooks.slack.com/services/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');
    }

    public function send($msg = '')
    {
        if ($msg) {
            $this->client->send($msg);
        }
    }

}

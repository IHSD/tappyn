<?php defined("BASEPATH") or exit('No direct script access ALLOWED');

class Feed_library {
    protected $client;

    public function __construct()
    {
        $this->client = new GetStream\Stream\Client('r9h8b7nn5gra', 'bsvv4zhw4qgak8h6jd45cdardsdy877n6k5jru7v8vqc4xxa5w8gzf3f9667ea56');
        $this->user_feed_1 = $this->client->feed('tappyn', '1');
    }

    public function addToFeed()
    {
        $data = [
            "actor" => "User:2",
            "verb" => "pin",
            "object" => "Place:42",
            "target" => "oard:1"
        ];
        try {
            $this->user_feed_1->addActivity($data);
            echo "Feed updated";
        } catch(Exception $e)
        {
            die(var_dump($e));
        }
    }

    public function fetch()
    {
        $results = $this->user_feed_1->getActivities();
        echo json_encode($results);
        die();
    }

    public function ()
    {

    }
}

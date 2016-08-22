<?php defined("BASEPATH") or exit('No direct script access allowed');

use FacebookAds\Api;
use FacebookAds\Object\Ad;
use FacebookAds\Object\AdCreative;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\AdCreativeFields;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Fields\AdImageFields;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\Fields\ObjectStorySpecFields;
use FacebookAds\Object\Fields\ObjectStory\LinkDataFields;
use FacebookAds\Object\Fields\TargetingSpecsFields;
use FacebookAds\Object\ObjectStorySpec;
use FacebookAds\Object\ObjectStory\LinkData;
use FacebookAds\Object\TargetingSpecs;
use FacebookAds\Object\Values\AdObjectives;
use FacebookAds\Object\Values\BillingEvents;
use FacebookAds\Object\Values\OptimizationGoals;

class Ad_lib
{
    /**
     * I would move these to a config file
     * @var array
     */
    private $fb_setting = array(
        'bussiness_id' => 'act_502815969898232',
        'app_id'       => '1018237411624257',
        'app_secret'   => 'd4b026673315e0be1d6123c53cf34aa2',
        'fan_id'       => '446303965572597',
        'ig_id'        => '1027980790609615',
        'token'        => 'EAAOeFN83hUEBAIbSV1ynUOEBQmqPsqdEicikxnK9oWJDzQhVRHLoNUQfR0r3RJxB7kJIZC7NZCZBHFoZCztFgiT09z0uJF7htlIVrLWAd1i8PPc6PK773XDAb84DZALfaspFqUdyweuCzjVLicpEfccojcZB2MxDzN0TrSDIJmsAZDZD',
    );

    public function __construct()
    {
        $this->load->model('ad_model');
        $this->load->model('contest');
        $this->load->model('submission');
    }

    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function go_test_by_company($contest_id, $submission_ids, $content)
    {
        $data    = array();
        $contest = $this->contest->get($contest_id);
        foreach ($submission_ids as $sid) {
            $data[] = array(
                'contest_id'    => $contest->id,
                'submission_id' => $sid,
                'platform'      => $contest->platform,
                'get_id'        => 'by_company',
                'content'       => $content,
            );
        }
        return $this->ad_model->create_by_array($data);
    }

    public function graph_ctr()
    {
        try {
            $ungraphs = $this->ad_model->get_ungraph();
            foreach ($ungraphs as $ungraph) {
                if ($ungraph->platform == 'facebook' || $ungraph->platform == 'instagram') {
                    $this->graph_facebook($ungraph);
                }
            }
        } catch (Exception $e) {
            echo 'Exception:' . $e->getMessage();
        }
    }

    private function graph_facebook($ad_model)
    {
        $ad_id = $ad_model->get_id;
        $url   = 'https://graph.facebook.com/v2.6/' . $ad_id . '/insights?fields=ctr&access_token=' . $this->fb_setting['token'];
        $done  = 99;
        $ctr   = '0.00';
        $tmp   = file_get_contents($url);
        if ($tmp) {
            $result = json_decode($tmp, true);
            $done   = 2;
            if ($result && $result['data']['ctr']) {
                $ctr  = round($result['data']['ctr'], 2);
                $done = 1;
            }
        }
        $this->submission->update($ad_model->submission_id, array('ctr' => $ctr));
        $this->ad_model->update($ad_model->id, array('done' => $done));
    }

    public function check_unsend()
    {
        $params = array(
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >'  => date('Y-m-d H:i:s'),
            'paid'         => 1,
        );
        $contests = $this->contest->fetchAll($params);
        foreach ($contests as $contest) {
            if ($contest->submission_count < $contest->submission_limit) {
                continue;
            }

            $done = $this->ad_model->select_by_contest_id($contest->id);
            //var_dump($done);
            if ($done->num_rows() > 0) {
                continue;
            }

            $method_name = $contest->platform . '_ad';
            $submissions = $this->contest->submissions($contest->id);
            if ($submissions && method_exists($this, $method_name)) {
                $this->$method_name($contest, $submissions);
                //$this->twitter_ad($contest, $submissions);
            }
            break;
        }
        //var_dump($contests);
    }

    private function twitter_ad($contest, $submissions)
    {
        echo 'twitter';
    }

    private function facebook_api_init()
    {
        Api::init(
            $this->fb_setting['app_id'], // App ID
            $this->fb_setting['app_secret'],
            $this->fb_setting['token']// Your user access token
        );
    }

    private function instagram_ad($contest, $submissions)
    {
        $this->facebook_ad($contest, $submissions, 'instagram');
    }

    private function facebook_ad($contest, $submissions, $ad_type = 'facebook')
    {
        $result = array();

        try {
            $this->facebook_api_init();
            $campaign = new Campaign(null, $this->fb_setting['bussiness_id']);
            $campaign->setData(array(
                CampaignFields::NAME      => 'api contest:' . $contest->id . ' ' . $ad_type,
                CampaignFields::OBJECTIVE => AdObjectives::LINK_CLICKS,
            ));

            $campaign->create(array(
                Campaign::STATUS_PARAM_NAME => Campaign::STATUS_PAUSED,
            ));
            //sleep(1);

            $targeting                                        = new TargetingSpecs();
            $targeting->{TargetingSpecsFields::GEO_LOCATIONS} =
            array(
                'countries' => array('US'),
            );
            if ($ad_type == 'instagram') {
                $targeting->{TargetingSpecsFields::PAGE_TYPES} = array('instagramstream');
            }

            $start_time    = (new \DateTime(""))->format(DateTime::ISO8601);
            $end_date_time = (new \DateTime("+1 day"))->modify("+1 seconds");
            $end_time      = $end_date_time->format(DateTime::ISO8601);

            $adset = new AdSet(null, $this->fb_setting['bussiness_id']);
            $adset->setData(array(
                AdSetFields::NAME              => 'api contest:' . $contest->id,
                AdSetFields::OPTIMIZATION_GOAL => OptimizationGoals::REACH,
                AdSetFields::BILLING_EVENT     => BillingEvents::IMPRESSIONS,
                AdSetFields::BID_AMOUNT        => 2,
                AdSetFields::DAILY_BUDGET      => 1500,
                AdSetFields::CAMPAIGN_ID       => $campaign->id,
                AdSetFields::TARGETING         => $targeting,
                AdSetFields::START_TIME        => $start_time,
                AdSetFields::END_TIME          => $end_time,
            ));
            $adset->create(array(
                AdSet::STATUS_PARAM_NAME => AdSet::STATUS_PAUSED,
            ));
            //sleep(1);
            foreach ($submissions as $submission) {
                $img = 'forad.jpg';
                $ch  = curl_init($submission->attachment);
                $fp  = fopen($img, 'c');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
                $image                            = new AdImage(null, $this->fb_setting['bussiness_id']);
                $image->{AdImageFields::FILENAME} = $img;

                $image->create();
                unlink($img);
                //echo 'Image Hash: ' . $image->{AdImageFields::HASH} . PHP_EOL;

                $link_data = new LinkData();
                $link_data->setData(array(
                    LinkDataFields::MESSAGE    => $submission->text,
                    LinkDataFields::LINK       => 'https://fabel.us/contest/' . $contest->id,
                    LinkDataFields::CAPTION    => 'https://fabel.us/',
                    LinkDataFields::IMAGE_HASH => $image->{AdImageFields::HASH},
                ));

                $object_story_spec = new ObjectStorySpec();
                $object_story_spec->setData(array(
                    ObjectStorySpecFields::PAGE_ID   => $this->fb_setting['fan_id'],
                    ObjectStorySpecFields::LINK_DATA => $link_data,
                ));
                if ($ad_type == 'instagram') {
                    $object_story_spec->setData(array(
                        ObjectStorySpecFields::INSTAGRAM_ACTOR_ID => $this->fb_setting['ig_id'],
                    ));
                }

                $creative = new AdCreative(null, $this->fb_setting['bussiness_id']);
                $creative->setData(array(
                    AdCreativeFields::NAME              => 'Submission ' . $submission->id,
                    AdCreativeFields::OBJECT_STORY_SPEC => $object_story_spec,
                ));

                $creative->create();
                //sleep(1);

// Finally, create your ad along with ad creative.
                // Please note that the ad creative is not created independently, rather its
                // data structure is appended to the ad group
                $data = array(
                    AdFields::NAME     => 'Submission ' . $submission->id,
                    AdFields::ADSET_ID => $adset->id,
                    AdFields::CREATIVE => array(
                        'creative_id' => $creative->id,
                    ),
                );

                $ad = new Ad(null, $this->fb_setting['bussiness_id']);
                $ad->setData($data);
                $ad->create(array(
                    Ad::STATUS_PARAM_NAME => Ad::STATUS_PAUSED,
                ));

                $content = array(
                    'campaign' => $campaign->id,
                    'adset'    => $adset->id,
                    'img_hash' => $image->{AdImageFields::HASH},
                    'creative' => $creative->id,
                    'ad'       => $ad->id,
                );
                $result[] = array(
                    'contest_id'    => $contest->id,
                    'submission_id' => $submission->id,
                    'platform'      => $ad_type,
                    'get_id'        => $ad->id,
                    'content'       => serialize($content),
                    'end_time'      => $end_date_time->getTimestamp(),
                );

                sleep(1);
                break;

            }
            if ($this->db->insert_batch('ads', $result)) {
                echo 'contest ' . $contest->id . ' auto add success';
            }

        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }

        //var_dump($creative);

    }

}

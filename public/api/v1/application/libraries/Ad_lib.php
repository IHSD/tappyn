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
    private $data;
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

    public function check_unsend()
    {
        $params = array(
            'start_time <' => date('Y-m-d H:i:s'),
            'stop_time >' => date('Y-m-d H:i:s'),
            'paid' => 1,
        );
        $contests = $this->contest->fetchAll($params);
        foreach ($contests as $contest) {
            if ($contest->submission_count < $contest->submission_limit) {
                //continue;
            }

            $done = $this->ad_model->select_by_contest_id($contest->id);
            //var_dump($done);
            if ($done->num_rows() > 0) {
                continue;
            }
            $submissions = $this->contest->submissions($contest->id);
            if ($submissions) {
                $this->facebook_ad($contest, $submissions);
            }
            break;
        }
        //var_dump($contests);
    }

    private function instagram_ad($contest, $submissions)
    {
        $this->facebook_ad($contest, $submissions, 'instagram');
    }

    private function facebook_ad($contest, $submissions, $ad_type = 'facebook')
    {
        $bi = 'act_502815969898232';
        $i = '1018237411624257';
        $s = 'd4b026673315e0be1d6123c53cf34aa2';
        $img_hash = 'e8819ada075057d7071d73d519671dfc'; // test
        $fan_id = '446303965572597';
        $ig_id = '1027980790609615';
        $token = 'EAAOeFN83hUEBAIbSV1ynUOEBQmqPsqdEicikxnK9oWJDzQhVRHLoNUQfR0r3RJxB7kJIZC7NZCZBHFoZCztFgiT09z0uJF7htlIVrLWAd1i8PPc6PK773XDAb84DZALfaspFqUdyweuCzjVLicpEfccojcZB2MxDzN0TrSDIJmsAZDZD';
        $result = array();

        try {

            Api::init(
                $i, // App ID
                $s,
                $token // Your user access token
            );
            $campaign = new Campaign(null, $bi);
            $campaign->setData(array(
                CampaignFields::NAME => 'api contest:' . $contest->id . ' ' . $ad_type,
                CampaignFields::OBJECTIVE => AdObjectives::LINK_CLICKS,
            ));

            $campaign->create(array(
                Campaign::STATUS_PARAM_NAME => Campaign::STATUS_PAUSED,
            ));
            //sleep(1);

            $targeting = new TargetingSpecs();
            $targeting->{TargetingSpecsFields::GEO_LOCATIONS} =
            array(
                'countries' => array('US'),
            );
            if ($ad_type == 'instagram') {
                $targeting->{TargetingSpecsFields::PAGE_TYPES} = array('instagramstream');
            }

            $start_time = (new \DateTime(""))->format(DateTime::ISO8601);
            $end_time = (new \DateTime("+1 day"))->modify("+1 seconds")->format(DateTime::ISO8601);

            $adset = new AdSet(null, $bi);
            $adset->setData(array(
                AdSetFields::NAME => 'api contest:' . $contest->id,
                AdSetFields::OPTIMIZATION_GOAL => OptimizationGoals::REACH,
                AdSetFields::BILLING_EVENT => BillingEvents::IMPRESSIONS,
                AdSetFields::BID_AMOUNT => 2,
                AdSetFields::DAILY_BUDGET => 1500,
                AdSetFields::CAMPAIGN_ID => $campaign->id,
                AdSetFields::TARGETING => $targeting,
                AdSetFields::START_TIME => $start_time,
                AdSetFields::END_TIME => $end_time,
            ));
            $adset->create(array(
                AdSet::STATUS_PARAM_NAME => AdSet::STATUS_PAUSED,
            ));
            //sleep(1);
            foreach ($submissions as $submission) {
                $img = 'forad.jpg';
                $ch = curl_init($submission->attachment);
                $fp = fopen($img, 'c');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
                $image = new AdImage(null, $bi);
                $image->{AdImageFields::FILENAME} = $img;

                $image->create();
                unlink($img);
                //echo 'Image Hash: ' . $image->{AdImageFields::HASH} . PHP_EOL;

                $link_data = new LinkData();
                $link_data->setData(array(
                    LinkDataFields::MESSAGE => $submission->text,
                    LinkDataFields::LINK => 'https://tappyn.com/contest/' . $contest->id,
                    LinkDataFields::CAPTION => 'https://tappyn.com/',
                    LinkDataFields::IMAGE_HASH => $image->{AdImageFields::HASH},
                ));

                $object_story_spec = new ObjectStorySpec();
                $object_story_spec->setData(array(
                    ObjectStorySpecFields::PAGE_ID => $fan_id,
                    ObjectStorySpecFields::LINK_DATA => $link_data,
                ));
                if(){

                }

                $creative = new AdCreative(null, $bi);
                $creative->setData(array(
                    AdCreativeFields::NAME => 'Submission ' . $submission->id,
                    AdCreativeFields::OBJECT_STORY_SPEC => $object_story_spec,
                ));

                $creative->create();
                //sleep(1);

// Finally, create your ad along with ad creative.
                // Please note that the ad creative is not created independently, rather its
                // data structure is appended to the ad group
                $data = array(
                    AdFields::NAME => 'Submission ' . $submission->id,
                    AdFields::ADSET_ID => $adset->id,
                    AdFields::CREATIVE => array(
                        'creative_id' => $creative->id,
                    ),
                );

                $ad = new Ad(null, $bi);
                $ad->setData($data);
                $ad->create(array(
                    Ad::STATUS_PARAM_NAME => Ad::STATUS_PAUSED,
                ));
                sleep(1);
                // break;
                $content = array(
                    'campaign' => $campaign->id,
                    'adset' => $adset->id,
                    'img_hash' => $image->{AdImageFields::HASH},
                    'creative' => $creative->id,
                    'ad' => $ad->id,
                );
                $result[] = array(
                    'contest_id' => $contest->id,
                    'submission_id' => $submission->id,
                    'platform' => 'facebook',
                    'get_id' => $ad->id,
                    'content' => serialize($content),
                );

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

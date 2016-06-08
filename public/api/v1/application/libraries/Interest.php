<?php defined("BASEPATH") or exit('No direct script access allowed');

class Interest
{
    public $data = array(
        'pets' => array('id' => '1', 'text' => 'Pets', 'slug' => 'pets'),
        'food_beverage' => array('id' => '2', 'text' => 'Food & Drink', 'slug' => 'food_beverage'),
        'finance_business' => array('id' => '3', 'text' => 'Business & Finance', 'slug' => 'finance_business'),
        'health_wellness' => array('id' => '4', 'text' => 'Health & Fitness', 'slug' => 'health_wellness'),
        'travel' => array('id' => '5', 'text' => 'Travel', 'slug' => 'travel'),
        'social_network' => array('id' => '6', 'text' => 'Social & Gaming', 'slug' => 'social_network'),
        'home_garden' => array('id' => '7', 'text' => 'Home & Garden', 'slug' => 'home_garden'),
        'education' => array('id' => '8', 'text' => 'Education', 'slug' => 'education'),
        'art_entertainment' => array('id' => '9', 'text' => 'Art & Entertainment', 'slug' => 'art_entertainment'),
        'fashion_beauty' => array('id' => '10', 'text' => 'Fashion & Beauty', 'slug' => 'fashion_beauty'),
        'sports_outdoors' => array('id' => '11', 'text' => 'Sports & Outdoors', 'slug' => 'sports_outdoors'),
        'tech_science' => array('id' => '12', 'text' => 'Tech & Scienc', 'slug' => 'tech_science'),
    );
    public $id_data = array();

    public function __construct()
    {
        foreach ($this->data as $key => $data) {
            $this->id_data[$data['id']] = $data;
        }
    }
    public function __get($var)
    {
        return get_instance()->$var;
    }

    public function get_user_interests($user_id = 0)
    {
        $result = array();
        if (!$user_id) {
            return $result;
        }
        $user_interests = $this->db->select('interest_id')->from('users_interests')->where('user_id', $user_id)->get();
        if ($user_interests->num_rows() > 0) {
            foreach ($user_interests->result() as $interest) {
                if ($this->id_data[$interest->interest_id]) {
                    $result[] = $this->id_data[$interest->interest_id]['slug'];
                }
            }
        }
        return $result;
    }

    public function add_user_interests($user_id = 0, $interests = array())
    {
        if (!$user_id || !$interests || !is_array($interests)) {
            return false;
        }
        $sql = $interest_ids = array();
        $ts = time();
        foreach ($interests as $interest_slug) {
            if ($this->data[$interest_slug]) {
                $interest_id = $this->data[$interest_slug]['id'];
                $interest_ids[] = $interest_id;
                $sql[] = array('user_id' => $user_id, 'interest_id' => $interest_id, 'created_at' => $ts);
            }
        }
        if (count($interest_ids) < 3) {
            return false;
        }

        if ($user_id == 'check_interests') {
            return true;
        }

        if ($this->db->from('users_interests')->where('user_id', $user_id)->delete() &&
            $this->db->insert_batch('users_interests', $sql)) {
            return true;
        } else {
            return 'db error';
        }
    }
}

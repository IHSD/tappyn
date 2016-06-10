<?php defined("BASEPATH") or exit('No direct script access allowed');

class Interest
{
    public $data = array(
        'business' => array('id' => '1', 'text' => 'Business', 'slug' => 'business'),
        'entertainment' => array('id' => '2', 'text' => 'Entertainment', 'slug' => 'entertainment'),
        'family_relationships' => array('id' => '3', 'text' => 'Family & Relationships', 'slug' => 'family_relationships'),
        'fitness_wellness' => array('id' => '4', 'text' => 'Fitness and Wellness', 'slug' => 'fitness_wellness'),
        'food_drink' => array('id' => '5', 'text' => 'Food and Drink', 'slug' => 'food_drink'),
        'hobbies' => array('id' => '6', 'text' => 'Hobbies', 'slug' => 'hobbies'),
        'shopping_fashion' => array('id' => '7', 'text' => 'Shopping and Fashion', 'slug' => 'shopping_fashion'),
        'sports_outdoors' => array('id' => '8', 'text' => 'Sports & Outdoors', 'slug' => 'sports_outdoors'),
        'technology' => array('id' => '9', 'text' => 'Technology', 'slug' => 'technology'),
        'pets' => array('id' => '10', 'text' => 'Pets', 'slug' => 'pets'),
        'travel' => array('id' => '11', 'text' => 'Travel', 'slug' => 'travel'),
        'education' => array('id' => '12', 'text' => 'Education', 'slug' => 'education'),
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

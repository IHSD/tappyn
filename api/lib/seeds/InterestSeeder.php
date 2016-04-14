<?php

use Phinx\Seed\AbstractSeed;

class InterestSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = array(
            array(
                'id' => 1,
                'name' => 'interests',
                'display_name' => 'Interests',
                'lft' => 1,
                'rgt' => 20
            ),
            array(
                'id' => 2,
                'name' => 'food_beverage',
                'display_name' => "Food And Beverage",
                'lft' => 2,
                'rgt' => 3
            ),
            array(
                'id' => 3,
                'name' => 'finance_business',
                'display_name' => "Finance and Business",
                'lft' => 4,
                'rgt' => 5
            ),
            array(
                'id' => 4,
                'name' => 'health_wellness',
                'display_name' => "Health and Wellness",
                'lft' => 6,
                'rgt' => 7
            ),
            array(
                'id' => 5,
                'name' => 'travel',
                'display_name' => "Travel",
                'lft' => 8,
                'rgt' => 9,
            ),
            array(
                'id' => 6,
                'name' => 'social_network',
                'display_name' => "Social Network"      ,
                'lft' => 10,
                'rgt' => 11
            ),
            array(
                'id' => 7,
                'name' => 'home_garden',
                'display_name' => "Home and Garden",
                'lft' => 12,
                'rgt' => 13
            ),
            array(
                'id' => 8,
                'name' => 'education',
                'display_name' => "Education",
                'lft' => 14,
                'rgt' => 15
            ),
            array(
                'id' => 9,
                'name' => 'art_entertainment',
                'display_name' => "Art and Entertainment",
                'lft' => 16,
                'rgt' => 17
            ),
            array(
                'id' => 10,
                'name' => "fashion_beauty",
                'display_name' => "Fashion and Beauty",
                'lft' => 18,
                'rgt' => 19
            )
        );
        $this->insert('interests', $data);
    }
}

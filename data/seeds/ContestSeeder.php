<?php

use Phinx\Seed\AbstractSeed;

class ContestSeeder extends AbstractSeed
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
        $objectives = array(
            'website_clicks',
            'close_sales',
            'brand_positioning',
            'exposure'
        );

        $platforms = array(
            'facebook',
            'twitter',
            'google',
            'general'
        );

        $industries = array(
            'health_wellness',
            'social_network',
            'food_beverage',
            'finance_business',
            'travel',
            'pets'
        );
        $faker = Faker\Factory::create();
        $ids = array();
        $companies = $this->fetchAll("SELECT * FROM users LEFT JOIN users_groups ON users.id = users_groups.user_id WHERE users_groups.group_id = 3");
        foreach($companies as $company)
        {
            $ids[] = $company['user_id'];
        }

        for($i = 0; $i < 100; $i++) {
            $data[] = [
                'title' => $faker->name,
                'owner' => $faker->randomElement($ids),
                'created_at' => date('Y-m-d H:i:s'),
                'start_time' => date('Y-m-d H:i:s'),
                'stop_time' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'submission_limit' => 50,
                'prize' => 20.00,
                'objective' => $faker->randomElement($objectives),
                'platform' => $faker->randomElement($platforms),
                'age' => 0,
                'gender' => 0,
                'location' => NULL,
                'audience' => $faker->text(),
                'different' => $faker->text(),
                'summary' => $faker->text(),
                'paid' => 1,
                'additional_images' => "[]",
                'industry' => $faker->randomElement($industries),
                'emotion' => NULL
            ];
        }
        $this->insert('contests', $data);
    }
}

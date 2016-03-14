<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
        $group_data = [];
        // // Generate the users
        $faker = Faker\Factory::create();
        $data = [];
        for($i = 3000; $i < 4000; $i ++)
        {
            $data[] = [
                'id' => $i,
                'email' => $faker->email,
                'password' => 'davol350',
                'ip_address' => '::1',
                'created_on' => time(),
                'last_login' => time(),
                'active' => 1,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'points' => 100,
                'facebook_login' => 1
            ];
            $group_data[] = [
                'user_id' => $i,
                'group_id' => 2
            ];
        }

        $this->insert('users', $data);
        $this->insert('users_groups', $group_data);
        // Add them to the group table

    }
}

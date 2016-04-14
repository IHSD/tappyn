<?php

use Phinx\Seed\AbstractSeed;

class SubmissionSeeder extends AbstractSeed
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
        $faker = Faker\Factory::create();
        // Get possible users
        $ids = array();
        $users = $this->fetchAll("SELECT * FROM users LEFT JOIN users_groups ON users.id = users_groups.user_id WHERE users_groups.group_id = 2");

        foreach($users as $user)
        {
            $ids[] = $user['user_id'];
        }
        // Get possible contests
        $cids = array();
        $contests = $this->fetchAll("SELECT * FROM contests WHERE stop_time > '".date('Y-m-d H:i:s')."';");
        foreach($contests as $contest)
        {
            $cids[] = $contest['id'];
        }

        $data = array();

        for($i = 0; $i < 1000; $i++)
        {
            $data[] = [
                'created_at' => date('Y-m-d H:i:s'),
                'owner' => $faker->randomElement($ids),
                'headline' => $faker->text(50),
                'description' => $faker->text(),
                'text' => $faker->text(),
                'link_explanation' => $faker->text(100),
                'trending' => NULL,
                'contest_id' => $faker->randomElement($cids)
            ];
        }
        $this->insert('submissions', $data);
    }
}

<?php

use Phinx\Seed\AbstractSeed;

class VoteSeeder extends AbstractSeed
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
        $uids = array();
        $users = $this->fetchAll("SELECT * FROM users LEFT JOIN users_groups ON users.id = users_groups.user_id WHERE users_groups.group_id = 2");

        foreach($users as $user)
        {
            $uids[] = $user['user_id'];
        }
        $sids = array();
        $cids = array();
        $submissions = $this->fetchAll("SELECT id, contest_id FROM submissions");

        foreach($submissions as $sub)
        {
            $sids[] = $sub['id'];
            $cids[] = $sub['contest_id'];
        }
        $data = [];
        for($i = 0; $i < 5000; $i++)
        {
            $data[] = [
                'user_id' => $faker->randomElement($uids),
                'contest_id' => $faker->randomElement($cids),
                'submission_id' => $faker->randomElement($sids),
                'created_at' => time()
            ];
        }
        $this->insert('votes', $data);
    }
}

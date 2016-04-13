<?php

use Phinx\Seed\AbstractSeed;

class NotificationSeeder extends AbstractSeed
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
        $submissions = $this->fetchAll("SELECT id FROM submissions");

        foreach($submissions as $sub)
        {
            $sids[] = $sub['id'];
        }
        $data = [];
        for($i = 0; $i < 1000; $i++)
        {
            $data[] = [
                'user_id' => $faker->randomElement($uids),
                'type' => 'submission_received_vote',
                'created' => time(),
                'read_at' => NULL,
                'read' => 0,
                'object_type' => 'submission',
                'object_id' => $faker->randomElement($sids)
            ];
        }
        $this->insert('notifications', $data);
    }
}

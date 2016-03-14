<?php

use Phinx\Seed\AbstractSeed;

class GroupSeeder extends AbstractSeed
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
        $data = array();
        $users = $this->fetchAll("SELECT * FROM users WHERE id NOT IN (SELECT user_id FROM users_groups)");
        foreach($users as $user)
        {
            $data[] = [
                'user_id' => $user['id'],
                'group_id' => 2
            ];
        }
        $this->insert('groups', $data);
    }
}

<?php

use Phinx\Seed\AbstractSeed;

class AuthSeeder extends AbstractSeed
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
                'name' => "admin",
                'description' => "Administrator"
            ),
            array(
                'id' => 2,
                'name' => "member",
                'description' => "Members who submit content"
            ),
            array(
                'id' => 3,
                'name' =>'company',
                'description' => "Copmanies who sign up to create contests"
            )
        );
        $groups = $this->table('groups');
        $groups->insert($data)->save();

        $data = array(
            array(
                'id' => 1,
                'ip_address' => '127.0.0.1',
                'username' => 'administrator',
                'password' => '$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36',
                'salt' => '',
                'email' => 'admin@admin.com',
                'activation_code' => '',
                'forgotten_password_code' => NULL,
                'created_on' => time(),
                'last_login' => time(),
                'active' => 1,
                'first_name' => 'Admin',
                'last_name' => 'istrator',
                'company' => 'ADMIN',
                'phone' => '0'
            ),

        );
        $users = $this->table('users');
        $users->insert($data)->save();

        $data = array(
            array(
                'id' => 1,
                'user_id' => 1,
                'group_id' => 1
            ),
            array(
                'id' => 2,
                'user_id' => 1,
                'group_id' => 2,
            ),
            array(
                'id' => 3,
                'user_id' => 1,
                'group_id' => 3
            )
        );

        $ugroups = $this->table('users_groups');
        $ugroups->insert($data)->save();
    }
}

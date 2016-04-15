<?php

use Phinx\Migration\AbstractMigration;

class UsersInterestTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $uinterests = $this->table('users_interests');
        $uinterests->addColumn('user_id', 'integer', array('limit' => 11, 'null' => FALSE, 'signed' => FALSE))
                   ->addColumn('interest_id', 'integer', array('limit' => 11, 'null' => FALSE))
                   ->addForeignKey('user_id', 'users', 'id', array('delete' => "CASCADE", 'update' => "NO_ACTION"))
                   ->addForeignKey('interest_id', 'interests', 'id', array('delete' => "CASCADE", 'update' => "NO_ACTION"))
                   ->create();
    }
}

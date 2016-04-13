<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class CreateNotificationTable extends AbstractMigration
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
        $notifications = $this->table('notifications');
        $notifications->addColumn('user_id', 'integer', array('limit' => 11, 'signed' => FALSE, 'null' => FALSE))
                      ->addColumn('type', 'string', array('limit' => 45, 'null' => FALSE))
                      ->addColumn('created', 'integer', array('limit' => 11, 'null' => FALSE))
                      ->addColumn('read_at', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                      ->addColumn('read', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 0))
                      ->addColumn('object_type', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                      ->addColumn('object_id', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                      ->addForeignKey('user_id', 'users', 'id', array('delete' => 'CASCADE', 'update' => 'NO_ACTION'))
                      ->create();
    }
}

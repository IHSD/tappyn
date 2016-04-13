<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class AddRequestsTable extends AbstractMigration
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
        $requests = $this->table('requests');
        $requests->addColumn('user_id', 'integer', array('limit' => 11, 'null' => FALSE))
                 ->addColumn('company_id', 'integer', array('limit' => 11, 'null' => FALSE))
                 ->addColumn('requested_at', 'integer', array('limit' => 11, 'null' => FALSE))
                 ->addColumn('fulfilled', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 0))
                 ->create();
    }
}

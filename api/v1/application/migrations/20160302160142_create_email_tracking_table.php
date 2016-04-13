<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateEmailTrackingTable extends AbstractMigration
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
        $emails = $this->table('emails');
        $emails->addColumn('to', 'string', array('limit' => 100, 'null' => FALSE))
               ->addColumn('event', 'string', array('limit' => 45, 'null' => FALSE))
               ->addColumn('created', 'integer', array('limit' => 11, 'null' => FALSE))
               ->addColumn('opened', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 0))
               ->create();
    }
}

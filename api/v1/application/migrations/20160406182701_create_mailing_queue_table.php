<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMailingQueueTable extends AbstractMigration
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
        $mailing_queue = $this->table('mailing_queue');
        $mailing_queue->addColumn('queued_at', 'integer', array('null' => FALSE))
                      ->addColumn('sent_at', 'integer', array('null' => TRUE, 'default' => NULL))
                      ->addColumn('failure_reason', 'string', array('limit' => 245, 'default' => NULL, 'null' => TRUE))
                      ->addColumn('recipient', 'string', array('limit' => 145, 'null' => FALSE))
                      ->addColumn('recipient_id', 'integer', array('limit' => 11, 'null' => FALSE))
                      ->addColumn('email_type', 'string', array('limit' => 45, 'null' => FALSE))
                      ->addColumn('processing', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 0))
                      ->addColumn('object_type', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                      ->addColumn('object_id', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                      ->create();
    }
}

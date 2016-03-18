<?php

use Phinx\Migration\AbstractMigration;

class PendingFeedEvents extends AbstractMigration
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
        $pending_feed_events = $this->table('pending_feed_events');
        $feed->addColumn('event', 'string', array('limit' => 45, 'null' => FALSE))
             ->addColumn('actor', 'string', array('limit' => 45, 'null' => FALSE))
             ->addColumn('verb', 'string', array('limit' => 45, 'null' => FALSE))
             ->addColumn('object', 'string', array('limit' => 45, 'null' => FALSE))
             ->addColumn('target', 'string', array('limit' => 45, 'null' => FALSE))
             ->addColumn('created', 'integer', array('limit' => 11, 'null'=> FALSE))
             ->create();
    }
}

<?php

use Phinx\Migration\AbstractMigration;

class CreateTrackedEventsTable extends AbstractMigration
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
        $events = $this->table('tracked_events');
        $events->addColumn('session_hash', 'string', array('limit' => 45))
               ->addColumn('created_at', 'integer', array('limit' => 11, 'default' => NULL, 'null' => TRUE))
               ->addColumn('event_name', 'string', array('limit' => 45, 'default' => NULL, 'null' => TRUE))
               ->addColumn('object_type', 'string', array('limit' => 45, 'default' => NULL, 'null' => TRUE))
               ->addColumn('object_id', 'integer', array('limit' => 11, 'default' => NULL, 'null' => TRUE))->create();
    }
}

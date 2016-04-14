<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAnalyticsTable extends AbstractMigration
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
        $analytics = $this->table('analytics_sessions');
        $analytics->addColumn('session_hash', 'string', array('limit' => 100))
                  ->addColumn('referrer', 'string', array('limit' => 100, 'default' => NULL))
                  ->addColumn('user_agent', 'string', array('limit' => 150))
                  ->addColumn('is_mobile', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => NULL))
                  ->addColumn('family', 'string', array('limit' => 45))
                  ->addColumn('major', 'integer', array('limit' => 11))
                  ->addColumn('os', 'string', array('limit' => 45))
                  ->addColumn('os_major', 'integer', array('limit' => 11))
                  ->addColumn('ip_address', 'string', array('limit' => 45))
                  ->addColumn('domain', 'string', array('limit' => 45))
                  ->addColumn('country', 'string', array('limit' => 45))
                  ->addColumn('state', 'string', array('limit' => 45))
                  ->addColumn('town', 'string', array('limit' => 45))
                  ->addColumn('created_at', 'integer', array('limit' => 11, 'null' => FALSE))
                  ->addIndex('session_hash', array('unique' => TRUE))
                  ->create();
    }
}

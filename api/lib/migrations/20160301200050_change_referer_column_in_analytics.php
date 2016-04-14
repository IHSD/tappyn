<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class ChangeRefererColumnInAnalytics extends AbstractMigration
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
        $analytics->changeColumn('referrer', 'string', array('limit' => 100, 'default' => NULL, 'null' => TRUE))
                  ->changeColumn('user_agent', 'string', array('limit' => 150, 'null' => TRUE))
                  ->changeColumn('is_mobile', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => NULL, 'null' => TRUE))
                  ->changeColumn('family', 'string', array('limit' => 45, 'null' => TRUE))
                  ->changeColumn('major', 'integer', array('limit' => 11, 'null' => TRUE))
                  ->changeColumn('os', 'string', array('limit' => 45, 'null' => TRUE))
                  ->changeColumn('os_major', 'integer', array('limit' => 11, 'null' => TRUE))
                  ->changeColumn('ip_address', 'string', array('limit' => 45, 'null' => TRUE))
                  ->changeColumn('domain', 'string', array('limit' => 45, 'null' => TRUE))
                  ->changeColumn('country', 'string', array('limit' => 45, 'null' => TRUE))
                  ->changeColumn('state', 'string', array('limit' => 45, 'null' => TRUE))
                  ->changeColumn('town', 'string', array('limit' => 45, 'null' => TRUE))
        ->update();
    }
}

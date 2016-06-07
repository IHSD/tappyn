<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class CreateContestsTable extends AbstractMigration
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
        if($this->hasTable('contests')) return;
        $contests = $this->table('contests');
        $contests->addColumn('owner', 'integer', array('limit' => 11, 'null' => FALSE))
                 ->addColumn('title', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('time_length', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('start_time', 'timestamp', array('null' => FALSE))
                 ->addColumn('stop_time', 'timestamp', array('null' => FALSE))
                 ->addColumn('submission_limit', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 50))
                 ->addColumn('prize', 'float', array('limit' => '8,2', 'default' => 50))
                 ->addColumn('objective', 'string', array('limit' => 45, 'default' => NULL, 'null' => TRUE))
                 ->addColumn('platform', 'string', array('limit' => 45, 'default' => NULL, 'null' => TRUE))
                 ->addColumn('age', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => NULL, 'null' => TRUE))
                 ->addColumn('gender', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => NULL, 'null' => TRUE))
                 ->addColumn('location', 'string', array('limit' => 45, 'default' => NULL, 'null' => TRUE))
                 ->addColumn('audience', 'text', array('default' => NULL, 'null' => TRUE))
                 ->addColumn('different', 'text', array('default' => NULL, 'null' => TRUE))
                 ->addColumn('summary', 'string', array('limit' => 250, 'default' => NULL, 'null' => TRUE))
                 ->addColumn('additional_images', 'text', array('default' => NULL, 'null' => TRUE))
                 ->addColumn('paid', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'null' => FALSE))
                 ->create();
    }
}

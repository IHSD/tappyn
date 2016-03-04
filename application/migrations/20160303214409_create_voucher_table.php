<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateVoucherTable extends AbstractMigration
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
        $vouchers = $this->table('vouchers');
        $vouchers->addColumn('name', 'string', array('limit' => 45, 'null' => FALSE))
                 ->addColumn('expiration', 'string', array('limit' => 15, 'null' => FALSE, 'default' => 'time_length'))
                 ->addColumn('starts_at', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('ends_at', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('discount_type', 'string', array('limit' => 45, 'null' => FALSE, 'default' => 'percentage'))
                 ->addColumn('value', 'float', array('limit' => '8,2', 'null' => FALSE))
                 ->addColumn('code', 'string', array('limit' => 45, 'null' => FALSE))
                 ->addColumn('status', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => FALSE, 'default' => 1))
                 ->addColumn('usage_limit', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('times_used', 'integer', array('limit' => 11, 'default' => 0))
                 ->addColumn('created_at', 'integer', array('limit' => 11, 'null' => FALSE))
                 ->addColumn('updated_at', 'integer', array('limit' => 11, 'null' => FALSE))
                 ->addIndex('code', array('unique' => TRUE))
                 ->create();
    }
}

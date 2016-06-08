<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class CreatePayoutsTable extends AbstractMigration
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
        if($this->hasTable('payouts')) return;
        $payouts = $this->table('payouts');
        $payouts->addColumn('created_at', 'integer', array('limit' => 11, 'null' => FALSE))
                ->addColumn('claimed_at', 'integer', array('limit' => 11, 'null' => FALSE))
                ->addColumn('contest_id', 'integer', array('limit' => 11, 'null' => FALSE))
                ->addColumn('amount', 'integer', array('limit' => 11, 'null' => FALSE))
                ->addColumn('submission_id', 'integer', array('limit' => 11, 'null' => FALSE))
                ->addColumn('pending', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 1))
                ->addColumn('claimed', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'default' => 0))
                ->addColumn('user_id', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => FALSE))
                ->addColumn('account_id', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                ->addColumn('transfer_id', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                ->addColumn('account_type', 'string', array('limit' => 15, 'null' => FALSE, 'default' => 'stripe'))
                ->addColumn('notes', 'text', array('null' => TRUE, 'default' => NULL))
                ->create();
    }
}

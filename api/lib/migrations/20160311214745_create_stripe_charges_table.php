<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class CreateStripeChargesTable extends AbstractMigration
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
        if($this->hasTable('stripe_charges')) return;
        $stripe_charges = $this->table('stripe_charges');
        $stripe_charges->addColumn('charge_id', 'string', array('limit' => 45, 'null' => FALSE))
                       ->addColumn('contest_id', 'integer', array('limit' => 11, 'null' => FALSE))
                       ->addColumn('amount', 'integer', array('limit' => 11, 'null' => FALSE))
                       ->addColumn('captured', 'integer', array('limit' => MysqlAdapter::INT_TINY))
                       ->addColumn('created', 'integer', array('limit' => 11, 'null' => FALSE))
                       ->addColumn('currency', 'string', array('limit' => 11))
                       ->addColumn('customer', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                       ->addColumn('paid', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => FALSE, 'default' => NULL))
                       ->addColumn('source', 'string', array('limit' => 45, 'null' => FALSE))
                       ->addColumn('status', 'string', array('limit' => 15, 'null'  => FALSE))
                       ->create();
    }
}

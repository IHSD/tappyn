<?php

use Phinx\Migration\AbstractMigration;

class CreateStripeTransfersTable extends AbstractMigration
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
        if($this->hasTable('stripe_transfers')) return;
        $stripe_transfers = $this->table('stripe_transfers');
        $stripe_transfers->addColumn('transfer_id', 'string', array('limit' => 45, 'null' => FALSE))
                         ->addColumn('destination', 'string', array('limit' => 45, 'null' => FALSE))
                         ->addColumn('description', 'string', array('limit' => 45, 'null' => FALSE))
                         ->addColumn('amount', 'integer', array('limit' => 11, 'null' => FALSE))
                         ->addColumn('created_at', 'integer', array('limit' => 11, 'null' => FALSE))
                         ->addColumn('payout_id', 'integer', array('limit' => 11, 'null' => FALSE))
                         ->create();
    }
}

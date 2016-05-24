<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class CreateStripeAccountsTable extends AbstractMigration
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
        if($this->hasTable('stripe_accounts')) return;
        $stripe_accounts = $this->table('stripe_accounts');
        $stripe_accounts->addColumn('account_id', 'string', array('limit' => 45, 'null' => FALSE))
                        ->addColumn('user_id', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => FALSE))
                        ->addColumn('publishable_key', 'string', array('limit' => 45, 'null' => FALSE))
                        ->addColumn('secret_key', 'string', array('limit' => 45, 'null' => FALSE))
                        ->addColumn('transfers_enabled', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => FALSE, 'default' => 0))
                        ->create();
    }
}

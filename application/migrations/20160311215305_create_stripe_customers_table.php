<?php

use Phinx\Migration\AbstractMigration;

class CreateStripeCustomersTable extends AbstractMigration
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
        if($this->hasTable('stripe_customers')) return;
        $stripe_customers = $this->table('stripe_customers');
        $stripe_customers->addColumn('customer_id', 'string', array('limit' => 45, 'null' => FALSE))
                         ->addColumn('created', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                         ->addColumn('currency', 'string', array('limit' => 3, 'null' => TRUE, 'default' => NULL))
                         ->addColumn('default_source', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                         ->addColumn('email', 'string', array('limit' => 100, 'null' => TRUE, 'default' => NULL))
                         ->addColumn('user_id', 'integer', array('limit' => 11, 'null' => FALSE))
                         ->addColumn('updated_at', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                         ->create();
    }
}

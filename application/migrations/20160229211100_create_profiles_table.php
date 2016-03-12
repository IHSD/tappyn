<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
class CreateProfilesTable extends AbstractMigration
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
        if($this->hasTable('profiles')) return;
        $profiles = $this->table('profiles');
        $profiles->addColumn('logo_url', 'string', array('limit' => 500, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('mission', 'string', array('limit' => 500, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('extra_info', 'string', array('limit' => 500, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('name', 'string', array('limit' => 100, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('age', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('gender', 'integer', array('limit' => MysqlAdapter::INT_TINY, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('state', 'string', array('limit' => 50, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('school', 'string', array('limit' => 100, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('company_email', 'string', array('limit' => 100, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('company_url', 'string', array('limit' => 200, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('facebook_url', 'string', array('limit' => 200, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('twitter_handle', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                 ->addColumn('different', 'text', array('null' => TRUE, 'default' => NULL))
                 ->addColumn('summary', 'text', array('null' => TRUE, 'default' => NULL))
                 ->addColumn('stripe_customer_id', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                 ->create();
    }
}

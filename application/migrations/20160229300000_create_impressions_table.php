<?php

use Phinx\Migration\AbstractMigration;

class CreateImpressionsTable extends AbstractMigration
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
        if($this->hasTable('impressions')) return;
        $impressions = $this->table('impressions');
        $impressions->addColumn('contest_id', 'integer', array('limit' => 11, 'null' => FALSE))
                    ->addColumn('ip_address', 'string', array('limit' => 45, 'null' => TRUE, 'default' => NULL))
                    ->addColumn('created_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
                    ->addColumn('user_agent', 'string', array('limit' => 200, 'default' => NULL, 'null' => TRUE))
                    ->create();
    }
}

<?php

use Phinx\Migration\AbstractMigration;

class CreateSubmissiinsTable extends AbstractMigration
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
        if($this->hastable('submissions')) return;
        $submissions = $this->table('submissions');
        $submissions->addColumn('created_at', 'timestamp', array('default' => "CURRENT_TIMESTAMP"))
                    ->addColumn('updated_at', 'timestamp', array('default' => '0000-00-00 00:00:00'))
                    ->addColumn('owner', 'integer', array('limit' => 11, 'null' => FALSE))
                    ->addColumn('attachment', 'string', array('limit' => 500, 'null' => TRUE, 'default' => NULL))
                    ->addColumn('headline', 'string', array('limit' => 100, 'null' => TRUE, 'default' => NULL))
                    ->addColumn('description', 'string', array('limit' => 500, 'null' => TRUE, 'default' => NULL))
                    ->addColumn('text', 'text', array('null' => TRUE, 'default' => NULL))
                    ->addColumn('link_explanation', 'string', array('limit' => 100, 'null' => TRUE, 'default' => NULL))
                    ->addColumn('trending', 'string', array('limit' => 100, 'null' => TRUE, 'default' => NULL))
                    ->addColumn('contest_id', 'integer', array('limit' => 11, 'null' => TRUE, 'default' => NULL))
                    ->create();
    }
}

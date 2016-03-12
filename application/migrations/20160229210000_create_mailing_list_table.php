<?php

use Phinx\Migration\AbstractMigration;

class CreateMailingListTable extends AbstractMigration
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
        if($this->hasTable('mailing_list')) return;
        $mailing_list = $this->table('mailing_list');
        $mailing_list->addColumn('email', 'string', array('limit' => 100, 'null' => FALSE))
                     ->addColumn('creted_at', 'timestamp', array('default' => 'CURRENT_TIMESTAMP'))
                     ->create();
    }
}

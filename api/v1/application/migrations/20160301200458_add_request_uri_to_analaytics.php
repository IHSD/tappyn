<?php

use Phinx\Migration\AbstractMigration;

class AddRequestUriToAnalaytics extends AbstractMigration
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
        $analytics = $this->table('analytics_sessions');
        $analytics->addColumn('request_uri', 'string', array('limit' => 45, 'default' => NULL, 'null' => TRUE))->update();
    }
}

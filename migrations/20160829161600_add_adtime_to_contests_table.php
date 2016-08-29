<?php

use Phinx\Migration\AbstractMigration;

class AddAdtimeToContestsTable extends AbstractMigration
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
        $contests = $this->table('contests');

        if (!$contests->hasColumn('test_start_time')) {
            $contests->addColumn('test_start_time', 'datetime')->update();
        }

        if (!$contests->hasColumn('test_upload_time')) {
            $contests->addColumn('test_upload_time', 'datetime')->update();
        }
    }
}

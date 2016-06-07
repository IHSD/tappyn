<?php

use Phinx\Migration\AbstractMigration;

class AddBoxToContestsTable extends AbstractMigration
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

        if (!$contests->hasColumn('location_box')) {
            $contests->addColumn('location_box', 'integer', array('limit' => 11, 'null' => false, 'default' => 0))->update();
        }

        if (!$contests->hasColumn('additional_info_box')) {
            $contests->addColumn('additional_info_box', 'integer', array('limit' => 11, 'null' => false, 'default' => 0))->update();
        }
    }
}

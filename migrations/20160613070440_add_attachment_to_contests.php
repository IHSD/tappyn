<?php

use Phinx\Migration\AbstractMigration;

class AddAttachmentToContests extends AbstractMigration
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

        if (!$contests->hasColumn('attachment')) {
            $contests->addColumn('attachment', 'string', array('limit' => 500, 'null' => true, 'default' => null))->update();
        }

        if (!$contests->hasColumn('use_attachment')) {
            $contests->addColumn('use_attachment', 'integer', array('limit' => 11, 'default' => 0))->update();
        }

        if (!$contests->hasColumn('tone_of_voice_box')) {
            $contests->addColumn('tone_of_voice_box', 'string', array('limit' => 100, 'null' => true, 'default' => null))->update();
        }
    }
}

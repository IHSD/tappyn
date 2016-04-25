<?php

use Phinx\Migration\AbstractMigration;

class AddWebsiteAndFacebookClicksToColumn extends AbstractMigration
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
        $contests->addColumn('website_clicks', 'integer', array('limit' => 11, 'null' => FALSE, 'default' => 0))
                 ->addColumn('facebook_clicks', 'integer', array('limit' => 11, 'null' => FALSE, 'default' => 0))
                 ->addColumn('twitter_clicks', 'integer', array('limit' => 11, 'null' => FALSE, 'default' => 0))
                 ->update();
    }
}

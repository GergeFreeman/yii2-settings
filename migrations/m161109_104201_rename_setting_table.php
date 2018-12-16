<?php

use yii\db\Migration;

/**
 * Class m161109_104201_rename_setting_table
 */
class m161109_104201_rename_setting_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        if (Yii::$app->db->schema->getTableSchema('{{%setting}}') === null) {
            $this->renameTable('{{%Setting}}', '{{%setting}}');
        }
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        if (Yii::$app->db->schema->getTableSchema('{{%Setting}}') === null) {
            $this->renameTable('{{%setting}}', '{{%Setting}}');
        }
    }
}

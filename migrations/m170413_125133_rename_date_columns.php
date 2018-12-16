<?php

use yii\db\Migration;

/**
 * Class m170413_125133_rename_date_columns
 */
class m170413_125133_rename_date_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->renameColumn('{{%setting}}', 'createdAt', 'created_at');
        $this->renameColumn('{{%setting}}', 'updatedAt', 'updated_at');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->renameColumn('{{%setting}}', 'created_at', 'createdAt');
        $this->renameColumn('{{%setting}}', 'updated_at', 'updatedAt');
    }
}

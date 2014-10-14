<?php

use yii\db\Schema;
use yii\db\Migration;

class m141014_212645_profile extends Migration
{
    public function up()
    {
        return $this->createTable('profile', array(
            'id' => 'INT UNSIGNED PRIMARY KEY AUTO_INCREMENT',
            'uid' => 'INT UNSIGNED UNIQUE',
            'name' => 'VARCHAR(64) NULL',
        ));
    }

    public function down()
    {
        return $this->dropTable('profile');
    }
}

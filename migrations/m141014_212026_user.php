<?php

use yii\db\Schema;
use yii\db\Migration;

class m141014_212026_user extends Migration
{
    public function up()
    {
        return $this->createTable('user', array(
            'id' => 'INT UNSIGNED PRIMARY KEY AUTO_INCREMENT',
            'email' => 'VARCHAR(32) NOT NULL UNIQUE',
            'password_hash' => 'VARCHAR(60) NOT NULL UNIQUE',
            'activation_code' => 'VARCHAR(32) NULL',
            'is_active' => 'BIT NOT NULL DEFAULT 0',
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'last_login_at' => 'TIMESTAMP NULL',
            'activation_code_created_at' => 'TIMESTAMP NOT NULL',
        ));
    }

    public function down()
    {
        return $this->dropTable('user');
    }
}

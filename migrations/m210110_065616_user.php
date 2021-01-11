<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Class m210110_065616_user
 */
class m210110_065616_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'password' => Schema::TYPE_STRING .' NOT NULL',
            'firstname' => Schema::TYPE_STRING,
            'lastname' => Schema::TYPE_STRING,
            'date_of_birth' => Schema::TYPE_DATE,
            'gender' => Schema::TYPE_TINYINT,
            'social' => Schema::TYPE_STRING,
            'profile_image' => Schema::TYPE_STRING,
            'authKey' => Schema::TYPE_STRING,
            'accessToken' => Schema::TYPE_STRING,
        ]);
        $this->createIndex('email', 'user', 'email', TRUE);
        
        $security = Yii::$app->security;
        $this->batchInsert('user',
            ['email', 'password', 'firstname', 'lastname', 'date_of_birth', 'gender'], [
                ['admin@company.com', $security->generatePasswordHash('admin'), 'admin', 'admin', date('Y-m-d',mktime(0, 0, 0, 02, 25, 1982)),1],
                ['user1@company.com', $security->generatePasswordHash('user1'), 'user1', 'user1', date('Y-m-d',mktime(0, 0, 0, 02, 25, 1983)),0],
                ['user2@company.com', $security->generatePasswordHash('user2'), 'user2', 'user2', date('Y-m-d',mktime(0, 0, 0, 02, 25, 1984)),1],
                ['user3@company.com', $security->generatePasswordHash('user3'), 'user3', 'user3', date('Y-m-d',mktime(0, 0, 0, 02, 25, 1985)),0],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210110_065616_user cannot be reverted.\n";

        return false;
    }
    */
}

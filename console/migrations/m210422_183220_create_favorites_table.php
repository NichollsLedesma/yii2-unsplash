<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%favorites}}`.
 */
class m210422_183220_create_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%favorites}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'photo_id' => $this->string(20)->notNull(),
            'url' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

         $this->createIndex(
            'idx-favorite-user_id',
            'favorites',
            'user_id'
        );

        $this->addForeignKey(
            'fk-favorite-user_id',
            'favorites',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-favorite-user_id',
            'favorites'
        );

        $this->dropIndex(
            'idx-favorite-user_id',
            'favorites'
        );

        $this->dropTable('{{%favorites}}');
    }
}

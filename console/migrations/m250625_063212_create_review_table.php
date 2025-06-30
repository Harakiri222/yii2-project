<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%review}}`.
 */
class m250625_063212_create_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('review', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'text' => $this->text()->notNull(),
            'status' => "ENUM('pending', 'approved') NOT NULL DEFAULT 'pending'",
            'created_at' => $this->integer(),
        ]);

        $this->addForeignKey('fk_review_product', 'review', 'product_id', 'product', 'id', 'CASCADE');
        $this->addForeignKey('fk_review_user', 'review', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%review}}');
    }
}

<?php

use yii\db\Migration;

class m250625_063219_insert_seed_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time();

        $roles = ['user', 'moderator'];
        foreach ($roles as $role) {
            if (!(new \yii\db\Query())->from('auth_item')->where(['name' => $role])->exists()) {
                $this->insert('auth_item', [
                    'name' => $role,
                    'type' => 1,
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }

        $users = [
            ['user1', 'user'],
            ['user2', 'user'],
            ['user3', 'user'],
            ['mod1', 'moderator'],
            ['mod2', 'moderator'],
        ];

        $userIds = [];
        foreach ($users as [$username, $role]) {
            $user = (new \yii\db\Query())->from('user')->where(['username' => $username])->one();
            if (!$user) {
                $this->insert('user', [
                    'username' => $username,
                    'email' => "$username@example.com",
                    'password_hash' => Yii::$app->security->generatePasswordHash('password'),
                    'auth_key' => Yii::$app->security->generateRandomString(),
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
                $userId = $this->db->getLastInsertID();
            } else {
                $userId = $user['id'];
            }
            $userIds[$username] = $userId;

            if (!(new \yii\db\Query())->from('auth_assignment')->where(['item_name' => $role, 'user_id' => $userId])->exists()) {
                $this->insert('auth_assignment', [
                    'item_name' => $role,
                    'user_id' => $userId,
                    'created_at' => $time,
                ]);
            }
        }

        $productIds = [];
        for ($i = 1; $i <= 10; $i++) {
            $product = (new \yii\db\Query())->from('product')->where(['name' => "Product $i"])->one();
            if (!$product) {
                $this->insert('product', [
                    'name' => "Product $i",
                    'description' => "Description for product $i",
                    'created_at' => $time,
                ]);
                $productId = $this->db->getLastInsertID();
            } else {
                $productId = $product['id'];
            }
            $productIds[] = $productId;
        }

        $statuses = ['pending', 'approved'];

        for ($j = 1; $j <= 15; $j++) {
            $productId = $productIds[array_rand($productIds)];

            $randomUser = 'user' . rand(1, 3);
            $userId = $userIds[$randomUser];

            $this->insert('review', [
                'product_id' => $productId,
                'user_id' => $userId,
                'text' => "This is review #$j for product.",
                'status' => $statuses[array_rand($statuses)],
                'created_at' => $time - rand(0, 10000),
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('review');
        $this->delete('product');
        $this->delete('auth_assignment');
        $this->delete('user');
        $this->delete('auth_item');
    }
}
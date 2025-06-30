<?php

namespace api\modules\v1\models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function getRole()
    {
        return Yii::$app->authManager->getRolesByUser($this->id)[0]->name ?? null;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        try {
            $secret = Yii::$app->params['jwtSecret'];
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            $userId = $decoded->uid ?? null;

            if ($userId) {
                return static::findOne($userId);
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }
}
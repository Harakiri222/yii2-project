<?php

namespace api\modules\v1\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Review extends ActiveRecord
{
    public static function tableName()
    {
        return 'review';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => function() { return time(); },
            ],
        ];
    }

    public function rules()
    {
        return [
            [['product_id', 'user_id', 'text'], 'required'],
            [['product_id', 'user_id'], 'integer'],
            ['text', 'string', 'max' => 1000],
            [['status'], 'string'],
            [['status'], 'in', 'range' => ['pending', 'approved']],
        ];
    }

    public function fields()
    {
        $fields = parent::fields();

        $fields['user_name'] = function () {
            return $this->user ? $this->user->username : null;
        };

        $fields['product_name'] = function () {
            return $this->product ? $this->product->name : null;
        };

        return $fields;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
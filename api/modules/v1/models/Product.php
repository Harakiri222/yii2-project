<?php

namespace api\modules\v1\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    public static function tableName()
    {
        return 'product';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'description'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'description',
        ];
    }
}
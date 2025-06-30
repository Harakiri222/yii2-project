<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\Cors;
use yii\filters\auth\HttpBearerAuth;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
        ];

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    public function actionUserRole($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $assignment = (new \yii\db\Query())
            ->select('item_name')
            ->from('auth_assignment')
            ->where(['user_id' => $id])
            ->one();

        if (!$assignment) {
            throw new NotFoundHttpException('Role not found.');
        }

        return ['role' => $assignment['item_name']];
    }
}

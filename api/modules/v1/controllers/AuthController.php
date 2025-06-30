<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use Firebase\JWT\JWT;
use api\modules\v1\models\User;

class AuthController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        // Добавляем аутентификацию по Bearer-токену, но не на login
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['login'],
        ];

        return $behaviors;
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');

        $user = User::findByUsername($username);
        if (!$user || !$user->validatePassword($password)) {
            throw new UnauthorizedHttpException('Invalid credentials');
        }

        $key = Yii::$app->params['jwtSecret'];
        $payload = [
            'iss' => 'test-review',
            'aud' => 'vue-frontend',
            'iat' => time(),
            'exp' => time() + 3600 * 24,
            'uid' => $user->id,
            'username' => $user->username,
        ];

        $jwt = JWT::encode($payload, $key, 'HS256');

        return ['token' => $jwt];
    }
}
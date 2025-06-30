<?php

use yii\web\Response;

$params = require __DIR__ . '/params.php';
return [

    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\modules\v1\Module',
        ],
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=test_review',
            'username' => 'root',
            'password' => '2222',
            'charset' => 'utf8',
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
            'enableCsrfValidation' => false,
            'cookieValidationKey' => 'z8F2bR#xV7Qp9LsW@3uJeHdNf0YmTcX&',
        ],
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'response' => [
            'format' => Response::FORMAT_JSON,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                    'logFile' => '@runtime/logs/api.log',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                'POST api/login' => 'v1/auth/login',
                'GET api/user-role/<id:\d+>' => 'v1/user/user-role',
                'GET api/products' => 'v1/product/index',
                'GET api/products/<id:\d+>' => 'v1/product/view',
                'GET api/reviews' => 'v1/review/index',
                'POST api/reviews' => 'v1/review/create',
                'PUT api/reviews/<id:\d+>/approve' => 'v1/review/approve',
            ],
        ],
    ],
    'params' => $params,
];
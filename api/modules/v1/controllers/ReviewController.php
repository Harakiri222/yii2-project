<?php
namespace api\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\filters\auth\HttpBearerAuth;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ReviewController extends ActiveController
{
    public $modelClass = 'api\modules\v1\models\Review';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['index'],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['update'], $actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $model = new $this->modelClass();
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            Yii::$app->getResponse()->setStatusCode(201);
            return $model;
        } else {
            return $model->getErrors();
        }
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException("Review with ID $id not found.");
    }

    public function actionApprove($id)
    {
        if (!Yii::$app->user->can('moderator')) {
            throw new ForbiddenHttpException('Only moderators can approve reviews.');
        }

        $review = $this->findModel($id);
        $review->status = 'approved';

        if ($review->save()) {
            return $review;
        }

        return $review->getErrors();
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $expand = $request->get('expand');
        $productId = $request->get('product_id');
        $userId = $request->get('user_id');

        $query = $this->modelClass::find();

        if ($expand) {
            $relations = explode(',', $expand);
            $query->with($relations);
        }

        if ($productId !== null) {
            $query->andWhere(['product_id' => $productId]);
        }

        if ($userId !== null) {
            $query->andWhere(['user_id' => $userId]);
        }

        return $query->all();
    }
}
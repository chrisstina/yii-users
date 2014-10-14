<?php

namespace chriss\yiiusers\controllers;

use Yii;
use chriss\yiiusers\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProfileController implements the CRUD actions for Profile model.
 */
class ProfileController extends Controller
{

    public function actionIndex()
    {
        return $this->render('view', [
            'model' => $this->findProfile(),
        ]);
    }

    public function actionUpdate()
    {
        $model = $this->findProfile();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    protected function findProfile()
    {
        var_dump(Yii::$app->user->id);
        if (($profile = Profile::findByUserId(Yii::$app->user->id)) !== null) {
            return $profile;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}

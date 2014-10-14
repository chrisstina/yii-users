<?php

namespace app\modules\yiiusers\controllers;

use app\modules\yiiusers\models\User;
use app\modules\yiiusers\models\Profile;
use app\modules\yiiusers\models\LoginForm;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionRegister()
    {
        if ( ! Yii::$app->user->isGuest)
        {
            $this->goHome();
        }
        else
        {
            $user = new User();
            $profile = new Profile();

            if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post()))
            {
                if ($user->validate() && $profile->validate())
                {
                    if ($user->save(false))
                    {
                        $user->link('profile', $profile);
//                        $profile->uid = $user->id;
//                        $user->populateRelation('profile', $profile);
//                        $profile->save(false);
                        Yii::$app->session->setFlash('success',
                            'Activation code is sent'
                        );
                    }
                }
                else
                {
                    Yii::$app->session->setFlash('danger',
                        'Validation errors!'
                    );
                }
            }

            echo $this->render('registration', array('user' => $user, 'profile' => $profile));
        }
    }
    
    public function actionActivate()
    {
        $user = new User();
        $user->email = Yii::$app->request->get('email');
        $user->activation_code = Yii::$app->request->get('code');
        if ($user->activate())
        {
            $user->login();
            $this->redirect('profile');
        }
        else
        {
            Yii::$app->session->setFlash('danger',
                    'Activation failed'
                );
        }
        
        echo $this->render('activation', array('model' => $user));
    }
    
    public function actionResendActivationCode()
    {
        
    }
    
    public function actionLogin()
    {
        if ( ! Yii::$app->user->isGuest)
        {
            $this->goHome();
        }
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->getUser()->login())
            {
                return $this->goBack();
            }
        }
        
        return $this->render('login', ['model' => $model]);
    }
}

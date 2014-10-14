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
        Yii::$app->session->removeAllFlashes();
        
        if ( ! Yii::$app->user->isGuest)
        {
            return $this->goHome();
        }
        $user = new User();
        $profile = new Profile();

        if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post()))
        {
            if ($user->validate() && $profile->validate() && $user->save(false))
            {
                $user->link('profile', $profile);
                if (Yii::$app->getModule('yiiusers')->requiresEmailConfirmation)
                {
                    $user->sendActivationCode();
                    Yii::$app->session->setFlash('success', 
                            'Activation code is sent to ' . $user->email
                    );

                    return $this->goHome();
                }
                else
                {
                    Yii::$app->session->setFlash('success', 
                            'You have successfully registered ' . $user->email . ' account'
                    );

                    $user->login();
                    return $this->goToProfile();
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
    
    public function actionActivate()
    {
        Yii::$app->session->removeAllFlashes();
        
        $user = new User();
        $user->email = Yii::$app->request->get('email');
        $user->activation_code = Yii::$app->request->get('code');
        if ($user->activate())
        {
            Yii::$app->session->setFlash('success', 
                'You have successfully activated ' . $user->email . ' account'
            );

            $user->login();
            return $this->goToProfile();
        }
        else
        {
            Yii::$app->session->setFlash('danger',
                    'Activation failed: invalid activation code or user is already activated.'
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
            return $this->goHome();
        }
        
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if ($model->getUser()->login())
            {
                return $this->goToProfile();
            }
        }
        
        return $this->render('login', ['model' => $model]);
    }
    
    public function goHome()
    {
        return $this->redirect(Yii::$app->urlManager->createUrl('/yiiusers/default/index'));
    }
    
    public function goToProfile()
    {
        return $this->redirect(Yii::$app->urlManager->createUrl('/yiiusers/profile'));
    }
}

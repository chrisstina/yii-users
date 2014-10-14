<?php

namespace app\modules\yiiusers\models;

/**
 * Module login form
 *
 * @author chriss
 */
class LoginForm extends UserForm
{
    public $username;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'User email',
            'password' => 'Password',
        ];
    }
    
    public function validatePassword($attribute, $params)
    {
        $user = $this->getActiveUser();
        if ( ! $user || ! $user->validatePassword($this->$attribute))
        {
            $this->addError($attribute, 'Invalid password');
        }
    }
}
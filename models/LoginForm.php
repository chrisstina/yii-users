<?php

namespace app\modules\yiiusers\models;

use yii\base\Model;

/**
 * Module login form
 *
 * @author chriss
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    
    private $_user;

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
        $user = $this->getUser();
        if ( ! $user || ! $user->validatePassword($this->$attribute))
        {
            $this->addError($attribute, 'Invalid password');
        }
    }
    
    public function getUser()
    {
        if ($this->_user === null)
            $this->_user = User::findByUsername($this->username);
        
        return $this->_user;
    }

}
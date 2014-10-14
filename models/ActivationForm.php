<?php

namespace app\modules\yiiusers\models;

use yii\base\Model;

/**
 * Module login form
 *
 * @author chriss
 */
class ActivationForm extends Model
{
    public $username;
    
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'hasValidCode'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'User email',
        ];
    }
    
    /**
     * 
     * @param type $attribute
     * @param type $params
     */
    public function hasValidCode($attribute, $params)
    {
        if ( ! $this->getUser()->hasValidActivationCode())
        {
            $this->addError($attribute, 'Activation code expired');
        }
    }
    
    public function getUser()
    {
        if ($this->_user === null)
            $this->_user = User::findByUsername($this->username);
        
        return $this->_user;
    }

}
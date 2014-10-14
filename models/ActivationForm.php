<?php

namespace chriss\yiiusers\models;

/**
 * Form for activation code request
 *
 * @author chriss
 */
class ActivationForm extends UserForm
{
    public $username;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
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
}
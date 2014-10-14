<?php

namespace chriss\yiiusers\models;

use yii\base\Model;

/**
 * Basic form model for user requests
 *
 * @author chriss
 */
abstract class UserForm extends Model
{
    private $_user;

    /**
     * Finds user record by user email with no regards to user status
     * @return User
     */
    public function getUser()
    {
        if ($this->_user === null)
            $this->_user = User::findByUsername($this->username);

        return $this->_user;
    }
    
    /**
     * Finds user record by user email. Returns only active user.
     * @return User
     */
    public function getActiveUser()
    {
        if ($this->_user === null)
            $this->_user = User::findActiveByUsername($this->username);

        return $this->_user;
    }
}
<?php

namespace chriss\yiiusers\components;

use Yii;
use yii\base\Component;

/**
 * Class for delivering various notifications to users
 * via email (e.g. registration confirmation).
 *
 * @author chriss
 */
class UserNotificationService extends Component
{
    /**
     * User who receives notifications
     * @var User
     */
    private $_receiver = null;
    
    public function setReceiver($user)
    {
        $this->_receiver = $user;
    }
    
    /**
     * Sends out an email with the link to confirm registration
     * 
     * @return bool
     */
    public function sendActivationCode()
    {
        return ($this->_receiver !== null) ?
            $this->module->mailer
                ->compose('activation-html', ['user' => $this->_receiver])
                ->setTo($this->_receiver->email)
                ->setSubject(Yii::$app->name . ' account activation')
                ->send() :
            false;
    }
}
<?php

namespace chriss\yiiusers;

use Yii;

/**
 * Custom user registration and profile module.
 * Provides features for email confirmation.
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'chriss\yiiusers\controllers';
    
    /**
     * Does user need to confirm registration via email
     * @var bool 
     */
    public $requiresEmailConfirmation = true;
    
    /**
     * Default sender email for confirmation mails
     * @var string 
     */
    public $addressFrom = 'noreply@yiiusers.my';
    
    /**
     * Time in seconds until activation code expires
     * @var int 
     */
    public $activationCodeLifetime = 3600;
    
    private $_mailer;

    public function init()
    {
        parent::init();
    }
    
    public function getMailer()
    {
        if ( ! isset($this->_mailer))
        {
            $this->_mailer = Yii::$app->getMailer();
            $this->_mailer->viewPath = '@app/modules/yiiusers/views/mail';
            $this->_mailer->messageConfig = ['from' => $this->addressFrom];
        }
        
        return $this->_mailer;
    }
}

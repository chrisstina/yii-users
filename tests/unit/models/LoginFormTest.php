<?php

use \app\modules\yiiusers\models\LoginForm;

/**
 * @author chriss
 */
class LoginFormTest  extends \Codeception\TestCase\Test
{
    const VALID_ACTIVE_USERNAME = 'test3@gmail.com';
    const VALID_PASSWORD = 'password';
    const VALID_INACTIVE_USERNAME = 'test1@gmail.com';
    const VALID_INACTIVE_PASSWORD = 'password';
    const INVALID_USERNAME = 'invalid';
    const INVALID_PASSWORD = 'invalid';
    
   /**
    * @var \UnitTester
    */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }
    
    public function testValidatePassword()
    {
        $form = new LoginForm();
        $form->username = self::VALID_USERNAME;
        $form->password = self::VALID_PASSWORD;
        $form->validatePassword('password', array());
        $this->assertEquals(0, count($form->getErrors('password')));
        
        $form = new LoginForm();
        $form->username = self::INVALID_USERNAME;
        $form->password = self::INVALID_PASSWORD;
        $form->validatePassword('password', array());
        $this->assertEquals(1, count($form->getErrors('password')));
        
        $form = new LoginForm();
        $form->username = self::VALID_INACTIVE_USERNAME;
        $form->password = self::VALID_INACTIVE_PASSWORD;
        $form->validatePassword('password', array());
        $this->assertEquals(1, count($form->getErrors('password')));
    }
}
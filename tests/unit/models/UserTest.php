<?php

use app\modules\yiiusers\models\User;

class UserTest extends \Codeception\TestCase\Test
{
    const VALID_ACTIVE_USERNAME = 'test3@gmail.com';
    const VALID_INACTIVE_USERNAME = 'test1@gmail.com';
    const INVALID_USERNAME = 'invalid';
    const VALID_ACTIVATION_CODE = 'ID2xBCgkFQOz2FcsmCh-bbLDlWccm6El';
    
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

    // tests
    public function testLogin()
    {
        $this->assertTrue(Yii::$app->user->isGuest);
        $user = User::findByUsername(self::VALID_ACTIVE_USERNAME);
        $lastLogin = $user->last_login_at;
        $user->login();
        $this->assertFalse(Yii::$app->user->isGuest);

        $userUpd = User::findByUsername(self::VALID_ACTIVE_USERNAME);
        $this->assertNotEquals($userUpd->last_login_at, $lastLogin);
        Yii::$app->user->logout();
    }
    
    public function testUpdateLastLoginDate()
    {
        $user = User::findByUsername(self::VALID_ACTIVE_USERNAME);
        $lastLogin = $user->last_login_at;
        $user->updateLastLoginDate();
        
        $userUpd = User::findByUsername(self::VALID_ACTIVE_USERNAME);
        $this->assertNotEquals($userUpd->last_login_at, $lastLogin);
    }
    
    public function testActivate()
    {
        $user = User::findByUsername(self::VALID_INACTIVE_USERNAME);
        $this->assertFalse($user->is_active);
        $user->activation_code = self::VALID_ACTIVATION_CODE;
        $user->activate();
        
        $userUpd = User::findByUsername(self::VALID_INACTIVE_USERNAME);
        $this->assertTrue($userUpd->is_active);
    }
    
    public function testRenderActivationLink()
    {
        $user = User::findByUsername(self::VALID_INACTIVE_USERNAME);
        $this->assertStringEndsWith(
                '/index.php?r=/yiiusers/default/activate&code=' . self::VALID_ACTIVATION_CODE . '&email' . self::VALID_INACTIVE_USERNAME, 
                $user->renderActivationLink());
    }
    
    public function testFindByUsername()
    {
        $user = User::findByUsername(self::VALID_INACTIVE_USERNAME);
        $this->assertNotEmpty($user);
        
        $user2 = User::findByUsername(self::VALID_ACTIVE_USERNAME);
        $this->assertNotEmpty($user2);
        
        $user3 = User::findByUsername('invalid');
        $this->assertFalse($user3);
    }
    
    public function testFindActiveByUsername()
    {
        $user = User::findByUsername(self::VALID_INACTIVE_USERNAME);
        $this->assertFalse($user);
        
        $user2 = User::findByUsername(self::VALID_ACTIVE_USERNAME);
        $this->assertNotEmpty($user2);
        
        $user3 = User::findByUsername('invalid');
        $this->assertFalse($user3);
    }
    
    public function testFindByActivationCode()
    {
        $user = new User();
        $user->email = self::VALID_INACTIVE_USERNAME;
        $user->activation_code = self::VALID_ACTIVATION_CODE;
        $this->assertNotEmpty($user);
        
        $user2 = new User();
        $user2->email = self::VALID_ACTIVE_USERNAME;
        $user2->activation_code = self::VALID_ACTIVATION_CODE;
        $this->assertFalse($user2);
    }
}
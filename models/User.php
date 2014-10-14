<?php

namespace chriss\yiiusers\models;

use chriss\yiiusers\components\UserNotificationService;

use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use \yii\helpers\Url;
use Yii;

/**
 * Custom user model implementing authorization routines.
 * User email serves as username.
 * Introduces account state (active / inactive) via email confirmation.
 * Relates to the user profile model.
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $activation_code
 * @property string $activation_code_created_at
 * @property string $created_at
 * @property string $last_login_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;
    public $password_confirm;
    
    /**
     * Singleton object that serves delivery of various user notifications
     * such as confirmation emails.
     * 
     * @var UserNotificationService
     */
    private $_notificator;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'password_confirm'], 'required'],
            [['email'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['password', 'password_confirm'], 'string', 'min' => 6, 'max' => 30],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
            [['email', 'password', 'password_confirm'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'last_login_at' => Yii::t('app', 'Last Login At'),
        ];
    }
    
    /**
     * Depending on the requiresEmailConfirmation module setting
     * generates activation code to confirm or sets state to active straight away.
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
        {
            if (Yii::$app->getModule('yiiusers')->requiresEmailConfirmation)
            {
                $this->setActivationCode();
            }
            else
            {
                $this->is_active = true;
            }
            
            $this->setPassword($this->password);
        }
        
        return parent::beforeSave($insert);
    }
    
    public function getNotificator()
    {
        if ($this->_notificator == null)
        {
            $this->_notificator = new UserNotificationService();
            $this->_notificator->setReceiver($this);
        }
        
        return $this->_notificator;
    }
    
    /**
     * Decorates standard Yii login routine,
     * updates last login date.
     * 
     * @return boolean
     */
    public function login()
    {
        if (Yii::$app->user->login($this))
        {
            $this->updateLastLoginDate();
            return true;
        }
        
        return false;
    }
    
    public function updateLastLoginDate()
    {
        $this->last_login_at = date('Y-m-d H:i:s');
        $this->update(false);
    }
    
    /**
     * Attempts to set user state to active 
     * in case code and email are provided.
     * 
     * @return boolean
     */
    public function activate()
    {
        if ($user = $this->findByActivationCode())
        {
            $user->activation_code = null;
            $user->activation_code_created_at = null;
            $user->is_active = true;
            $user->save(false);
            return $user;
        }
        
        return false;
    }
    
    /**
     * Generates activation link based on
     * stored user data the following way:
     * 
     * /yiiusers/default/activate&code=<activation_code>&email=<email>
     * 
     * @return string
     */
    public function renderActivationLink()
    {
        return Url::toRoute(['/yiiusers/default/activate', 'code' => $this->activation_code, 'email' => $this->email], true);
    }

    /**
     * Sends out mail message to the user
     * with generated account confirmation link.
     * 
     * @return bool
     */
    public function sendActivationCode()
    {
        return $this->notificator->sendActivationCode();
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
         return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
         return $this->id;
    }
    
    /**
     * For compatibility returns user email as username.
     * 
     * @return string
     */
    public function getUsername()
    {
         return $this->email;
    }
    
    /**
     * Finds a related profile record
     * @return Profile
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['uid' => 'id'])->inverseOf('user');
    }

    /**
     * Generates password hash based on naked pass provided
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    /**
     * Generates activation code hash
     */
    public function setActivationCode()
    {
        $this->activation_code = Yii::$app->security->generateRandomString();
        $this->activation_code_created_at = date('Y-m-d H:i:s');
    }
    
    /**
     * Compares password provided with stored hash
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
	{
	    return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
         return $this->auth_key === $authKey;
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
         return static::findOne($id);
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * Finds user record by username
     * 
     * @param string $username user email in this case
     * @return User
     */
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }
    
    /**
     * Finds activated user record by username
     * 
    * @param string $username user email in this case
     * @return User
     */
    public static function findActiveByUsername($username)
    {
        return static::findOne(['email' => $username, 'is_active' => 1]);
    }

    /**
     * Finds inactive user record by email and activation code
     * @return User
     */
    public function findByActivationCode()
    {
        return static::findOne(['email' => $this->email, 'activation_code' => $this->activation_code, 'is_active' => 0]);
    }
}

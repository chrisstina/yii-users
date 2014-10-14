<?php

namespace app\modules\yiiusers\models;

use app\modules\yiiusers\components\UserNotificationService;

use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use \yii\helpers\Url;
use Yii;

/**
 * This is the model class for table "user".
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
    
    public function renderActivationLink()
    {
        return Url::toRoute(['/yiiusers/default/activate', 'code' => $this->activation_code, 'email' => $this->email], true);
    }

    public function sendActivationCode()
    {
        return $this->notificator->sendActivationCode();
    }
    
    public function getAuthKey()
    {
         return $this->auth_key;
    }

    public function getId()
    {
         return $this->id;
    }
    
    public function getUsername()
    {
         return $this->email;
    }
    
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['uid' => 'id'])->inverseOf('user');
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function setActivationCode()
    {
        $this->activation_code = Yii::$app->security->generateRandomString();
        $this->activation_code_created_at = date('Y-m-d H:i:s');
    }
    
    public function validatePassword($password)
	{
	    return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
    
    public function validateAuthKey($authKey)
    {
         return $this->auth_key === $authKey;
    }

    public static function findIdentity($id)
    {
         return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    public static function findByUsername($username)
    {
        return static::findOne(['email' => $username]);
    }
    
    public static function findActiveByUsername($username)
    {
        return static::findOne(['email' => $username, 'is_active' => 1]);
    }

    public function findByActivationCode()
    {
        return static::findOne(['email' => $this->email, 'activation_code' => $this->activation_code, 'is_active' => 0]);
    }
}

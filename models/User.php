<?php

namespace app\modules\yiiusers\models;

use \yii\db\ActiveRecord;
use \yii\web\IdentityInterface;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $activation_code
 * @property string $created_at
 * @property string $last_login_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;
    public $password_confirm;
    
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
            'activation_code' => Yii::t('app', 'Activation Code'),
            'created_at' => Yii::t('app', 'Created At'),
            'last_login_at' => Yii::t('app', 'Last Login At'),
        ];
    }
    
    public function beforeSave($insert)
    {
        if ($this->isNewRecord)
        {
            $this->setActivationCode();
            $this->setPassword($this->password);
        }
        
        return parent::beforeSave($insert);
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->isNewRecord)
        {
            // send confirm
        }
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['uid' => 'id'])->inverseOf('user');
    }
    
    public function activate()
    {
        if ($user = $this->_findByActivationCode())
        {
            $user->activation_code = null;
            $user->is_active = true;
            return $user->save(false);
        }
        
        return false;
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

    public function validateAuthKey($authKey)
    {
         return $this->auth_key === $authKey;
    }
    
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    
    public function setActivationCode()
    {
        $this->activation_code = Yii::$app->security->generateRandomString();
    }
    
    public function validatePassword($password)
	{
	    return Yii::$app->security->validatePassword($password, $this->password_hash);
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
        return static::findOne(['email' => $username, 'is_active' => 1]);
    }

    private function _findByActivationCode()
    {
        return static::find()->where(array('email' => $this->email, 'activation_code' => $this->activation_code, 'is_active' => 0))->one();
    }
}

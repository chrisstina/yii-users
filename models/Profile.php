<?php

namespace chriss\yiiusers\models;

use \yii\db\ActiveRecord;

/**
 * Profile model is to store user personal data
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 */
class Profile extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
        ];
    }
    
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'uid'])->inverseOf('profile');
    }
    
    public static function findByUserId($uid)
    {
        return self::findOne(['uid' => $uid]);
    }
}

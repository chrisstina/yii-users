<?php

namespace app\modules\yiiusers\models;

use \yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "profile".
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
}

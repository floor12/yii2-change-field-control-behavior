<?php

namespace floor12\yii2ChangeFieldControlBehavior;

use Yii;

/**
 * This is the model class for table "changes".
 *
 * @property integer $id
 * @property string $class
 * @property string $property
 * @property integer $object_id
 * @property string $value_new
 * @property string $value_old
 * @property integer $created
 * @property string $ip
 * @property integer $approved
 * @property integer $canceled
 * @property integer $user_id
 *
 */
class Change extends \yii\db\ActiveRecord
{

    public static function unreaded_count()
    {
        return sizeof(self::find()->where(['canceled' => 0, 'approved' => 0])->all());
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'changes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class', 'property', 'object_id', 'value_new', 'value_old'], 'required'],
            [['object_id', 'approved', 'canceled'], 'integer'],
            [['value_new', 'value_old', 'ip'], 'string'],
            [['class', 'property'], 'string', 'max' => 30],
        ];
    }

    public function approve()
    {
        if (!$this->approved) {
            $this->approved = time();
            $this->save(false);
        }
    }

    public function cancel()
    {
        if (!$this->canceled) {
            $this->canceled = time();
            $this->save(false);
        }
    }

    public function getClass_name()
    {
        $classNames = [
            'common\models\Organization' => 'Профиль компании',
            'common\models\User' => 'Профиль пользователя',
        ];

        return $classNames[$this->class];
    }

    public function getProperty_label()
    {
        $object = new $this->class;
        return $object->attributeLabels()[$this->property];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'class' => Yii::t('app', 'Компонент'),
            'class_name' => Yii::t('app', 'Компонент'),
            'property' => Yii::t('app', 'Поле'),
            'property_label' => Yii::t('app', 'Поле'),
            'created' => Yii::t('app', 'Дата'),
            'object_id' => Yii::t('app', 'ID'),
            'value_old' => Yii::t('app', 'Старое значение'),
            'value_new' => Yii::t('app', 'Новое значение'),
        ];
    }
}

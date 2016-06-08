<?php
/**
 * Created by PhpStorm.
 * User: floor12
 * Date: 07.06.2016
 * Time: 16:29
 */

namespace floor12\yii2ChangeFieldControlBehavior;


use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;

class ChangeFiledControlBehavior extends Behavior
{
    public $fields;

    public function logChange()
    {
        $classname = $this->owner->className();
        $oldOwner = $classname::findOne($this->owner->id);

        if ($this->fields) foreach ($this->fields as $field) {
            if ($this->owner->$field != $oldOwner->$field) {
                $log = new Change();
                $log->class = $this->owner->className();
                $log->object_id = $this->owner->id;
                $log->value_new = $this->owner->$field;
                $log->value_old = $oldOwner->$field;
                $log->property = $field;
                $log->created = time();
                $log->user_id = \Yii::$app->user->id;
                $log->ip = \Yii::$app->request->userIP;
                $log->save();
            }
        }
    }

    public function changeBlock($property)
    {
        $change = Change::find()->where(['approved' => 0, 'canceled' => 0, 'class' => $this->owner->className(), 'object_id' => $this->owner->id, 'property' => $property])->one();
        if ($change)
            return Html::tag('div', \Yii::t('app', '<span class="glyphicon glyphicon-exclamation-sign"></span> Изменения этого поля еще не одобрены администратором.'), ['class' => 'help-block', 'style' => 'font-size: 90%; color: #F19A2C;']);
    }


    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'logChange',
        ];
    }

}
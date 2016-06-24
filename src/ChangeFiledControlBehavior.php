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

    public $log = true;


    const METHOD_POSTMODERATION = 0;
    const METHOD_PREMODERATION = 1;

    public $method = self::METHOD_POSTMODERATION;

    public function logChange()
    {
        if ($this->log) {

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
                    $log->pre = $this->method;
                    $log->created = time();
                    $log->user_id = \Yii::$app->user->id;
                    $log->ip = \Yii::$app->request->userIP;
                    $log->save();

                    if ($this->method == self::METHOD_PREMODERATION) {
                        $this->owner->$field = $oldOwner->$field;
                    }

                }
            }
        }
    }

    public function changeBlock($property)
    {
        $change = Change::find()->where(['approved' => 0, 'canceled' => 0, 'class' => $this->owner->className(), 'object_id' => $this->owner->id, 'property' => $property])->one();
        if ($change)
            if ($change->pre)
                return Html::tag('div', \Yii::t('app', '<span class="glyphicon glyphicon-exclamation-sign"></span> Изменение этого поля на "' . $change->value_new . '" ожидает проверки модератором.'), ['class' => 'help-block', 'style' => 'font-size: 90%; color: #F19A2C;']);
            else
                return Html::tag('div', \Yii::t('app', '<span class="glyphicon glyphicon-exclamation-sign"></span> Изменения этого поля еще не одобрены администратором.'), ['class' => 'help-block', 'style' => 'font-size: 90%; color: #F19A2C;']);

    }


    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_UPDATE => 'logChange',
        ];
    }

}
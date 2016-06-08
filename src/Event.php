<?php

namespace floor12\yii2ChangeFieldControlBehavior;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property integer $created
 * @property integer $updated
 * @property integer $create_user_id
 * @property integer $update_user_id
 * @property string $title
 * @property integer $type
 * @property string $description
 * @property integer $organization_id
 * @property \common\models\Organization $organization
 * @property \common\models\Member[] $members
 * @property integer $parent_id
 * @property string $place
 * @property string $type_string
 * @property integer status
 * @property string $address
 * @property integer $date_start
 * @property integer $date_end
 * @property integer $members_count
 * @property array $types
 */
class Event extends \common\models\MyActiveRecord
{

    public $types = [
        'Семинар',
        'Тренинг',
    ];

    public function getType_string()
    {
        return $this->types[$this->type];
    }

    /**
     * @inheritdoc
     */
    public
    static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public
    function rules()
    {
        return [
            [['title', 'type', 'description', 'organization_id', 'date_start', 'date_end'], 'required'],
            [['created', 'updated', 'create_user_id', 'update_user_id', 'type', 'organization_id', 'parent_id', 'date_start', 'date_end'], 'integer'],
            [['description', 'agenda'], 'string'],
            [['title', 'place', 'address'], 'string', 'max' => 255],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            ['type', 'in', 'range' => range(0, sizeof($this->types) - 1)],
            ['organization_id', 'in', 'range' => ArrayHelper::map(Organization::find()->all(), 'id', 'id')],
            ['parent_id', 'in', 'range' => ArrayHelper::map(Event::find()->all(), 'id', 'id')],
            ['date_end', 'compare', 'compareAttribute' => 'date_start', 'operator' => '>='],

        ];
    }

    /**
     * Получаем компания
     *
     * @return \common\models\Organization
     */

    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * @inheritdoc
     */

    public function beforeValidate()
    {
        if ($this->date_start)
            $this->date_start = strtotime($this->date_start);
        if ($this->date_end)
            $this->date_end = strtotime($this->date_end);
        return parent::beforeValidate();
    }

    /**
     * Получаем связанныые виды деятельности
     *
     * @return \common\models\Member[]
     */

    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['event_id' => 'id']);
    }

    /**
     * Получаем количество участников события
     *
     * @return integer
     */

    public function getMembers_count()
    {
        return sizeof($this->members);
    }

    public function addMember($data)
    {
        $user_id = $data;

        $member = Member::find()->where(['user_id' => (int)$user_id, 'event_id' => $this->id])->one();
        if ($member)
            return false;
        else {
            $member = new Member();
            $member->event_id = $this->id;
            $member->user_id = $user_id;
            $member->save();
            $this->refresh();
        }
    }


    /**
     * @inheritdoc
     */
    public
    function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'created' => Yii::t('app', 'Created'),
            'updated' => Yii::t('app', 'Updated'),
            'create_user_id' => Yii::t('app', 'Create User ID'),
            'update_user_id' => Yii::t('app', 'Update User ID'),
            'title' => Yii::t('app', 'Название мероприятия'),
            'type' => Yii::t('app', 'Тип'),
            'type_string' => Yii::t('app', 'Тип'),
            'description' => Yii::t('app', 'Описание'),
            'agenda' => Yii::t('app', 'Программа'),
            'organization_id' => Yii::t('app', 'Организатор'),
            'organization' => Yii::t('app', 'Организатор'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'place' => Yii::t('app', 'Место'),
            'address' => Yii::t('app', 'Адрес'),
            'date_start' => Yii::t('app', 'Начало'),
            'date_end' => Yii::t('app', 'Окончание'),
            'status' => Yii::t('app', 'Статус'),
            'filter' => Yii::t('app', 'Фильтр'),
            'members_count' => Yii::t('app', 'Участников'),
        ];
    }

    

}

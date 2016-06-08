<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\AdminFilter;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\Group */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Мероприятия';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php #echo  Html::a('Добавить группу', ['create'], ['class' => 'btn btn-success pull-right']) ?>
<h1><?= Html::encode($this->title) ?></h1>

<?php echo AdminFilter::widget([
    'modelClass' => '\common\models\Event',
    'fields' => [
        'status' => ['dropDownList', 'statuses'],
        'filter' => ['textInput']
    ]
]); ?>

<?php
Pjax::begin(['id' => 'gridPjax']);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-striped'],
    'rowOptions' => function ($model, $key, $index, $grid) {
        if ($model->status == \common\models\User::STATUS_DELETED) {
            return ['class' => 'lightText'];
        }
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'title',
        'type_string',
        'organization',
        'place',
        'date_start:date',

        'date_end:date',

        'members_count',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'headerOptions' => ['width' => '80'],
            'template' => '{view} {update} {delete}',
            'buttons' => [
                'view' => function ($url, $model) {
                    if ($model->status != \common\models\MyActiveRecord::STATUS_DELETED)
                        return Html::a(
                            '<span class="glyphicon glyphicon-eye-open" ></span > ',
                            $url, ['class' => 'btn btn-xs btn-success']);
                },
                'update' => function ($url, $model) {
                    if ($model->status != \common\models\MyActiveRecord::STATUS_DELETED)
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil" ></span > ',
                            $url, ['class' => 'btn btn-xs btn-warning']);
                },
                'delete' => function ($url, $model, $key) {
                    $options = array_merge([
                        'title' => Yii::t('yii', 'Delete'),
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                        'class' => 'btn btn-danger btn-xs'
                    ]);
                    if ($model->status != \common\models\MyActiveRecord::STATUS_DELETED)
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                }
            ]
        ],
    ],
]);
Pjax::end();
?>

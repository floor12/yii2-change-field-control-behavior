<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\components\AdminFilter;
use \yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Изменения';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php #echo  Html::a('Добавить группу', ['create'], ['class' => 'btn btn-success pull-right']) ?>
<h1><?= Html::encode($this->title) ?></h1>


<?php
Pjax::begin(['id' => 'gridPjax']);
echo GridView::widget(['dataProvider' => $dataProvider,
    'tableOptions' => [
        'class' => 'table table-striped'],
    'rowOptions' => function ($model, $key, $index, $grid) {
        if ($model->approved || $model->canceled) {
            return ['class' => 'lightText'];
        }
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        'class_name',
        'property_label',
        'object_id',
        'value_old',
        'value_new',
        'created:date',
        'ip',
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Действия',
            'headerOptions' => ['width' => '80'],
            'template' => '{edit} {approve} {cancel}',

            'buttons' => [
                'edit' => function ($url, $model) {
                    if (!$model->approved && !$model->canceled) {
                        $arr = explode('\\', $model->class);
                        $class = strtolower($arr[2]);
                        $url = '/' . $class . '/update?id=' . $model->object_id;
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil" ></span > ',
                            $url, ['class' => 'btn btn-xs btn-warning', 'title' => 'Одобрить']);
                    }
                },

                'approve' => function ($url, $model) {
                    if (!$model->approved && !$model->canceled)
                        return Html::a(
                            '<span class="glyphicon glyphicon-ok" ></span > ',
                            $url, ['class' => 'btn btn-xs btn-success', 'title' => 'Одобрить']);
                },
                'cancel' => function ($url, $model) {
                    if (!$model->approved && !$model->canceled)
                        return Html::a(
                            '<span class="glyphicon glyphicon-remove" ></span > ',
                            $url, ['class' => 'btn btn-xs btn-danger', 'title' => 'Отменить']);
                },

            ]
        ],
    ],
]);
Pjax::end();
?>

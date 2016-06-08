<?php
/**
 *
 * Created by PhpStorm.
 * User: floor12
 * Date: 28.05.2016
 * Time: 10:33
 *
 * @var $model \common\models\Event
 *
 */

$formatter = \Yii::$app->formatter;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>
<p><?= $model->description ?></p>


<div class="row">
    <div class="col-sm-3">
        Начало: <b><?= $formatter->asDate($model->date_start) ?></b>
    </div>
    <div class="col-sm-3">
        Окончание: <b><?= $formatter->asDate($model->date_end) ?></b>
    </div>
    <div class="col-sm-6">
        Место проведения: <b><?= $model->place ?></b> ( <?= $model->address ?>)
    </div>
    <div class="col-sm-3">
        Тип: <b><?= $model->type_string ?></b>
    </div>
    <div class="col-sm-3">
        Организатор: <b><?= $model->organization ?></b>
    </div>
</div>

<br><br>

<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Участники (<?= $model->members_count ?>)
    </div>
    <table class="table table-bordered">
        <tr>
            <th>Имя участника</th>
            <th>Роль в мероприятии</th>
            <th>Компания (должность)</th>
            <th>Дата регистрации</th>
        </tr>
        <?php if ($model->members) foreach ($model->members as $member) { ?>
            <tr>
                <td><?= \yii\helpers\Html::a($member, ['user/update', 'id' => $member->user->id]) ?></td>
                <td><?= $member->role_string ?></td>
                <td><?= $member->user->positions_current_string ?></td>
                <td><?= Yii::$app->formatter->asDate($member->created); ?></td>
            </tr>
        <?php } ?>

    </table>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(); ?>

<?= $form->errorSummary($model) ?>

<div class="row">
    <div class="col-md-9">

    </div>
    <div class="col-md-3">

    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'type')->dropDownList($model->types) ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'organization_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Organization::find()->orderBy('title')->all(), 'id', 'title')) ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'date_start', ['enableClientValidation' => false])->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => ['class' => 'form-control']
        ]); ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'date_end', ['enableClientValidation' => false])->widget(\yii\jui\DatePicker::classname(), [
            'language' => 'ru',
            'dateFormat' => 'dd.MM.yyyy',
            'options' => ['class' => 'form-control']
        ]) ?>
    </div>
</div>


<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'place')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'address')->textarea(['rows' => 2]) ?>

    </div>
</div>

<div class='form-group'>
    <?=
    $form->field($model, 'agenda')->widget(CKEditor::className(), [
        'options' => ['rows' => 4],
        'preset' => 'full'

    ])
    ?>
</div>


<?php
if (!$model->backlink)
    $model->backlink = Yii::$app->request->referrer;
echo $form->field($model, 'backlink')->hiddenInput()->label(false);
?>


<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>



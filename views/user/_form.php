<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\boostrap\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype'=>'multipart/form-data']
    ]); ?>

    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'firstname')->textInput(['autofocus' => true]) ?>
        </div>
        <div class="col-lg-5">
            <?= $form->field($model, 'lastname') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5" style="z-index: 1029">
            <?=
                $form->field($model, 'dateForScreen')->widget(DatePicker::classname(), [
                    'readonly' => true,
                    'options' => [
                        'placeholder' => 'Enter birth date ...',
                    ],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd/mm/yyyy',
                    ]
            ]);
            ?>
        </div>
        <div class="col-lg-5">
            <?= $form->field($model, 'uploaded_image')->fileInput() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'gender')->inline()->radioList(array('Male'=>'Male','Female'=>'Female')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'social')->inline()->checkboxList(array('Facebook'=>'Facebook','Twitter'=>'Twitter','Instagram'=>'Instagram','Line'=>'Line','Tiktok'=>'Tiktok')); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'email') ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-5">
            <?= $form->field($model, 'password')->passwordInput() ?>
        </div>
        <div class="col-lg-5">
            <?= $form->field($model, 'password_confirm')->passwordInput() ?>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

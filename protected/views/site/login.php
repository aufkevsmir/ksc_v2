<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Login';
$this->breadcrumbs = array('Login');
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card p-4 shadow-sm w-100" style="max-width: 450px;">
        <h4 class="mb-2 text-center">Login</h4>
        <p class="text-muted text-center mb-4">Please enter your email and password to access your account.</p>

        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        )); ?>

        <div class="mb-3">
            <?php echo $form->labelEx($model, 'email', ['class' => 'form-label']); ?>
            <?php echo $form->textField($model, 'email', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'email'); ?>
        </div>

        <div class="mb-3">
            <?php echo $form->labelEx($model, 'password', ['class' => 'form-label']); ?>
            <?php echo $form->passwordField($model, 'password', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>

        <div class="form-check mb-3">
            <?php echo $form->checkBox($model, 'rememberMe', ['class' => 'form-check-input', 'id' => 'rememberMe']); ?>
            <?php echo $form->label($model, 'rememberMe', ['class' => 'form-check-label', 'for' => 'rememberMe']); ?>
            <?php echo $form->error($model, 'rememberMe'); ?>
        </div>

        <div class="d-grid">
            <?php echo CHtml::submitButton('Login', ['class' => 'btn btn-ksc']); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

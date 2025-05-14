<?php
$this->pageTitle = Yii::app()->name . ' - Register';
$this->breadcrumbs = array('Register');
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card p-4 shadow-sm w-100" style="max-width: 500px;">
        <h4 class="mb-2 text-center">Create Your Account</h4>
        <p class="text-muted text-center mb-4">Please fill in the details below to register.</p>

        <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'register-form',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
        )); ?>

        <div class="mb-3">
            <?php echo $form->labelEx($model, 'full_name', ['class' => 'form-label']); ?>
            <?php echo $form->textField($model, 'full_name', ['class' => 'form-control']); ?>
            <?php echo $form->error($model, 'full_name'); ?>
        </div>

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

        <div class="mb-4">
            <?php echo $form->labelEx($model, 'role', ['class' => 'form-label']); ?>
            <?php echo $form->dropDownList($model, 'role', ['buyer' => 'Buyer', 'seller' => 'Seller'], ['class' => 'form-select']); ?>
            <?php echo $form->error($model, 'role'); ?>
        </div>

        <div class="d-grid">
            <button class="btn btn-ksc" type="submit">Register</button>
        </div>

        <?php $this->endWidget(); ?>
    </div>
</div>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'products-form',
    'enableAjaxValidation' => false,
)); ?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<div class="row">
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', array('size' => 60, 'maxlength' => 150)); ?>
    <?php echo $form->error($model, 'name'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'description'); ?>
    <?php echo $form->textArea($model, 'description', array('rows' => 6, 'cols' => 50)); ?>
    <?php echo $form->error($model, 'description'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'price'); ?>
    <?php echo $form->textField($model, 'price'); ?>
    <?php echo $form->error($model, 'price'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'stock'); ?>
    <?php echo $form->textField($model, 'stock'); ?>
    <?php echo $form->error($model, 'stock'); ?>
</div>

<div class="row">
    <?php echo $form->labelEx($model, 'image_url'); ?>
    <?php echo $form->textField($model, 'image_url', array('size' => 60, 'maxlength' => 255)); ?>
    <?php echo $form->error($model, 'image_url'); ?>
</div>

<div class="row buttons">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
</div>

<?php $this->endWidget(); ?>

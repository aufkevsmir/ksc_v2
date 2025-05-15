<?php
$form = $this->beginWidget('CActiveForm', [
    'id' => 'products-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data'],
]); 
?>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?php echo $form->errorSummary($model); ?>

<!-- Product Name -->
<div class="form-group mb-3">
    <?php echo $form->labelEx($model, 'name'); ?>
    <?php echo $form->textField($model, 'name', ['class' => 'form-control', 'maxlength' => 150]); ?>
    <?php echo $form->error($model, 'name'); ?>
</div>

<!-- Description -->
<div class="form-group mb-3">
    <?php echo $form->labelEx($model, 'description'); ?>
    <?php echo $form->textArea($model, 'description', ['rows' => 4, 'class' => 'form-control']); ?>
    <?php echo $form->error($model, 'description'); ?>
</div>

<!-- Price -->
<div class="form-group mb-3">
    <?php echo $form->labelEx($model, 'price'); ?>
    <?php echo $form->textField($model, 'price', ['class' => 'form-control']); ?>
    <?php echo $form->error($model, 'price'); ?>
</div>

<!-- Stock -->
<div class="form-group mb-3">
    <?php echo $form->labelEx($model, 'stock'); ?>
    <?php echo $form->textField($model, 'stock', ['class' => 'form-control']); ?>
    <?php echo $form->error($model, 'stock'); ?>
</div>

<!-- Category -->
<div class="form-group mb-3">
    <?php echo $form->labelEx($model, 'category'); ?>
    <?php echo $form->dropDownList($model, 'category', [
        '' => 'Select Category',
        'Electronics' => 'Electronics',
        'Apparel' => 'Apparel',
        'Home & Living' => 'Home & Living',
        'Beauty' => 'Beauty',
        'Sports' => 'Sports',
        'Automotive' => 'Automotive',
        'Toys' => 'Toys',
        'Office' => 'Office',
    ], ['class' => 'form-control']); ?>
    <?php echo $form->error($model, 'category'); ?>
</div>

<!-- Image Upload -->
<div class="form-group mb-4">
    <?php echo $form->labelEx($model, 'image_url'); ?>
    <?php echo $form->fileField($model, 'image_url', ['class' => 'form-control']); ?>
    <?php echo $form->error($model, 'image_url'); ?>
</div>

<!-- Submit -->
<div class="form-group">
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', ['class' => 'btn btn-primary']); ?>
</div>

<?php $this->endWidget(); ?>

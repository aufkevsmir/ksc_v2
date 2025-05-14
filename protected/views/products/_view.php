<?php
/* @var $this ProductsController */
/* @var $data Products */
?>

<div class="card mb-4 shadow-sm" style="max-width: 300px;">
    <?php if ($data->image_url): ?>
        <img src="<?php echo CHtml::encode($data->image_url); ?>" class="card-img-top" alt="<?php echo CHtml::encode($data->name); ?>" style="height: 200px; object-fit: cover;">
    <?php endif; ?>

    <div class="card-body">
        <h5 class="card-title">
            <?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id' => $data->id)); ?>
        </h5>

        <p class="card-text text-danger fw-bold">₱<?php echo number_format($data->price, 2); ?></p>

        <p class="card-text small text-muted">
            Stock: <?php echo CHtml::encode($data->stock); ?>
        </p>

        <a href="<?php echo Yii::app()->createUrl('products/view', ['id' => $data->id]); ?>" class="btn btn-sm btn-ksc">
            View Details
        </a>
    </div>
</div>

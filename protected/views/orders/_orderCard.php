<?php
/* @var $data Orders */
?>

<div class="card shadow-sm mb-3 p-3">
    <h5 class="mb-2">
        Order #<?= CHtml::encode($data->id); ?> - ₱<?= number_format($data->total_amount, 2); ?>
    </h5>

    <p class="mb-1">
        <strong>Buyer:</strong> <?= CHtml::encode($data->buyer->full_name); ?>
    </p>

    <p class="mb-3">
        <strong>Status:</strong> <?= ucfirst(CHtml::encode($data->status)); ?>
    </p>

    <a href="<?= Yii::app()->createUrl('orders/view', ['id' => $data->id]); ?>" class="btn btn-primary btn-sm">
        View & Approve
    </a>
</div>

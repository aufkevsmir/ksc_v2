<?php
/* @var $data Orders */
?>

<div class="card shadow-sm mb-3 p-3">
    <h5>Order #<?php echo $data->id; ?> - ₱<?php echo number_format($data->total_amount, 2); ?></h5>
    <p>Buyer: <?php echo CHtml::encode($data->buyer->full_name); ?></p>
    <p>Status: <strong><?php echo ucfirst($data->status); ?></strong></p>

    <a href="<?php echo Yii::app()->createUrl('orders/view', ['id' => $data->id]); ?>" class="btn btn-primary btn-sm">
        View & Approve
    </a>
</div>


<?php
/* @var $data Orders */
?>

<div class="list-group-item">
    <h6 class="fw-bold mb-1">Order #<?php echo $data->id; ?></h6>
    <p class="mb-1 text-muted">Buyer: <?php echo CHtml::encode($data->buyer->full_name); ?></p>
    <p class="mb-1">Total: ₱<?php echo number_format($data->total_amount, 2); ?> | Status: 
        <span class="badge bg-secondary"><?php echo ucfirst($data->status); ?></span>
    </p>
    <a href="<?php echo Yii::app()->createUrl('orders/view', ['id' => $data->id]); ?>" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
</div>

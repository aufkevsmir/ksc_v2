<?php
/* @var $data Orders */
?>

<div class="list-group-item">
    <h6 class="fw-bold mb-1">Order #<?php echo $data->id; ?> 
        <span class="badge bg-secondary ms-2"><?php echo ucfirst($data->status); ?></span>
    </h6>
    <p class="mb-1 text-muted">Seller: <?php echo CHtml::encode($data->seller->full_name); ?></p>
    <p class="mb-1">Total: ₱<?php echo number_format($data->total_amount, 2); ?></p>
    <a href="<?php echo Yii::app()->createUrl('orders/view', ['id' => $data->id]); ?>" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
</div>

<?php
/* @var $this OrdersController */
/* @var $model Orders */

$this->breadcrumbs = [
    'Orders' => ['index'],
    'Order #' . $model->id,
];

$statusClasses = [
    'pending' => 'secondary',
    'paid' => 'info',
    'shipped' => 'primary',
    'completed' => 'success',
    'cancelled' => 'danger',
];

$statusLabel = ucfirst($model->status);
$statusClass = $statusClasses[$model->status] ?? 'secondary';
$isBuyer = Yii::app()->user->getId() == $model->buyer_id;
$isSeller = Yii::app()->user->getId() == $model->seller_id;
?>

<div class="container mt-4">

    <!-- Order Details -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Order Details #<?php echo $model->id; ?></h4>
        </div>
        <div class="card-body">

            <?php $this->widget('zii.widgets.CDetailView', [
                'data' => $model,
                'htmlOptions' => ['class' => 'table table-bordered mb-0'],
                'attributes' => [
                    ['label' => 'Order ID', 'value' => $model->id],
                    ['label' => 'Buyer', 'value' => $model->buyer->full_name],
                    ['label' => 'Seller', 'value' => $model->seller->full_name],
                    ['label' => 'Total Amount', 'value' => '₱' . number_format($model->total_amount, 2)],
                    [
                        'label' => 'Status',
                        'type' => 'raw',
                        'value' => "<span class=\"badge bg-$statusClass\">$statusLabel</span>",
                    ],
                    ['label' => 'Shipping Address', 'value' => nl2br(CHtml::encode($model->shipping_address)), 'type' => 'raw'],
                    ['label' => 'Paid At', 'value' => $model->paid_at ? date('F j, Y g:i A', strtotime($model->paid_at)) : 'Not paid'],
                    ['label' => 'Created At', 'value' => $model->created_at],
                ],
            ]); ?>

            <!-- Buyer Payment Button -->
            <?php if ($model->status === 'pending' && $isBuyer): ?>
                <div class="mt-4">
                    <a href="<?php echo $this->createUrl('stripe/checkout', ['orderId' => $model->id]); ?>"
                       class="btn btn-success">
                        Pay with Stripe
                    </a>
                </div>
            <?php endif; ?>

            <!-- Seller Approval Button -->
            <?php if ($model->status === 'paid' && $isSeller): ?>
                <form method="post" action="<?php echo $this->createUrl('orders/approve', ['id' => $model->id]); ?>" class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Approve & Send Dispatch
                    </button>
                </form>
            <?php endif; ?>

            <!-- Print Dispatch Button -->
            <?php if ($isSeller || $isBuyer): ?>
                <a href="<?php echo $this->createUrl('orders/print', ['id' => $model->id]); ?>"
                   target="_blank"
                   class="btn btn-outline-secondary mt-3">
                   Print Dispatch Slip
                </a>
            <?php endif; ?>

        </div>
    </div>

    <!-- Order Items -->
    <div class="card shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">Order Items</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($model->orderItems as $item): ?>
                        <tr>
                            <td><?php echo CHtml::encode($item->product->name); ?></td>
                            <td>₱<?php echo number_format($item->price, 2); ?></td>
                            <td><?php echo $item->quantity; ?></td>
                            <td>₱<?php echo number_format($item->price * $item->quantity, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

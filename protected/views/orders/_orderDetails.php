<?php
/* @var $model Orders */
/* @var $showActions boolean */

$statusClasses = [
    'pending' => 'secondary',
    'paid' => 'info',
    'shipped' => 'primary',
    'completed' => 'success',
    'cancelled' => 'danger',
];

$statusLabel  = ucfirst($model->status);
$statusClass  = $statusClasses[$model->status] ?? 'secondary';
$isBuyer      = Yii::app()->user->getId() == $model->buyer_id;
$isSeller     = Yii::app()->user->getId() == $model->seller_id;
?>

<div class="container mt-4">

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Order #<?= $model->id; ?> Summary</h4>
        </div>

        <div class="card-body">

            <!-- Order Information -->
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr><th>Order ID</th><td><?= $model->id; ?></td></tr>
                    <tr><th>Buyer</th><td><?= CHtml::encode($model->buyer->full_name); ?></td></tr>
                    <tr><th>Seller</th><td><?= CHtml::encode($model->seller->full_name); ?></td></tr>
                    <tr><th>Total Amount</th><td>₱<?= number_format($model->total_amount, 2); ?></td></tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge bg-<?= $statusClass; ?>"><?= $statusLabel; ?></span></td>
                    </tr>
                    <tr>
                        <th>Shipping Address</th>
                        <td><?= nl2br(CHtml::encode($model->shipping_address)); ?></td>
                    </tr>
                    <tr>
                        <th>Paid At</th>
                        <td><?= $model->paid_at ? date('F j, Y g:i A', strtotime($model->paid_at)) : 'Not paid'; ?></td>
                    </tr>
                    <tr><th>Created At</th><td><?= $model->created_at; ?></td></tr>
                </tbody>
            </table>

            <!-- Order Items -->
            <h5 class="mb-3">Order Items</h5>
            <table class="table table-striped">
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
                            <td><?= CHtml::encode($item->product->name); ?></td>
                            <td>₱<?= number_format($item->price, 2); ?></td>
                            <td><?= $item->quantity; ?></td>
                            <td>₱<?= number_format($item->price * $item->quantity, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Action Buttons (Conditional) -->
            <?php if (!empty($showActions)): ?>
                <div class="mt-4">

                    <?php if ($model->status === 'pending' && $isBuyer): ?>
                        <a href="<?= $this->createUrl('stripe/checkout', ['orderId' => $model->id]); ?>" class="btn btn-success">
                            Pay with Stripe
                        </a>
                    <?php endif; ?>

                    <?php if ($model->status === 'paid' && $isSeller): ?>
                        <form method="post" action="<?= $this->createUrl('orders/approve', ['id' => $model->id]); ?>" class="d-inline-block">
                            <button type="submit" class="btn btn-primary">Approve & Send Dispatch</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($isBuyer || $isSeller): ?>
                        <a href="<?= $this->createUrl('orders/print', ['id' => $model->id]); ?>" target="_blank" class="btn btn-outline-secondary">
                            Print Dispatch Slip
                        </a>
                    <?php endif; ?>

                </div>
            <?php endif; ?>

        </div>
    </div>

</div>

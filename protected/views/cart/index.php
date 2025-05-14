<?php $this->breadcrumbs = ['My Cart']; ?>

<div class="card shadow-sm mb-5">
    <div class="card-body">
        <h4 class="mb-3">Shopping Cart</h4>

        <?php if (empty($cartItems)): ?>
            <div class="alert alert-warning">Your cart is empty.</div>
        <?php else: ?>
            <form action="<?php echo Yii::app()->createUrl('checkout/place'); ?>" method="post">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-center" style="width: 150px;">Unit Price</th>
                                <th class="text-center" style="width: 180px;">Quantity</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0; ?>
                            <?php foreach ($cartItems as $item): ?>
                                <?php
                                    $product = $item->product;
                                    $subtotal = $item->quantity * $product->price;
                                    $total += $subtotal;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="<?php echo $product->image_url ?: Yii::app()->baseUrl . '/images/no-image.png'; ?>"
                                                 alt="<?php echo CHtml::encode($product->name); ?>"
                                                 class="img-thumbnail"
                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                            <div>
                                                <strong><?php echo CHtml::encode($product->name); ?></strong><br>
                                                <span class="text-muted small d-block" style="max-width: 220px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;">
                                                    <?php echo CHtml::encode($product->description); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">₱<?php echo number_format($product->price, 2); ?></td>

                                    <td class="text-center">
                                        <div class="input-group input-group-sm justify-content-center" style="max-width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary py-0" onclick="decreaseQty(<?php echo $item->id; ?>)">−</button>
                                            <input type="number" id="qty-<?php echo $item->id; ?>"
                                                   name="quantity[<?php echo $item->id; ?>]"
                                                   value="<?php echo $item->quantity; ?>"
                                                   min="1"
                                                   max="<?php echo $product->stock; ?>"
                                                   class="form-control text-center p-0"
                                                   style="font-size: 0.9rem;">
                                            <button type="button" class="btn btn-outline-secondary py-0" onclick="increaseQty(<?php echo $item->id; ?>)">+</button>
                                        </div>
                                    </td>

                                    <td class="text-center">₱<?php echo number_format($subtotal, 2); ?></td>

                                    <td class="text-center">
                                        <a href="<?php echo Yii::app()->createUrl('cart/remove', ['id' => $item->id]); ?>" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <strong>Total:</strong>
                    <span class="text-danger fw-bold">₱<?php echo number_format($total, 2); ?></span>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?php echo Yii::app()->createUrl('cart/clear'); ?>" class="btn btn-outline-danger">
                        <i class="bi bi-trash3"></i> Clear Cart
                    </a>
                    <button type="submit" class="btn btn-ksc px-4">
                        <i class="bi bi-check-circle-fill"></i> Checkout
                    </button>
                </div>
            </div>

            </form>
        <?php endif; ?>
    </div>
</div>

<script>
function increaseQty(id) {
    const qty = document.getElementById('qty-' + id);
    const max = parseInt(qty.max);
    if (parseInt(qty.value) < max) qty.value = parseInt(qty.value) + 1;
}

function decreaseQty(id) {
    const qty = document.getElementById('qty-' + id);
    if (parseInt(qty.value) > 1) qty.value = parseInt(qty.value) - 1;
}
</script>

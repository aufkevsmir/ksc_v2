<?php


$isGuest  = Yii::app()->user->isGuest;
$isBuyer  = Yii::app()->user->getState('role') === 'buyer';
$loginUrl = Yii::app()->createUrl('site/login');
?>

<div class="row mb-5">
    <!-- LEFT: Image -->
    <div class="col-md-6">
        <?php if ($model->image_url): ?>
            <img src="<?php echo CHtml::encode($model->image_url); ?>"
                 class="img-fluid rounded mb-3"
                 style="max-height: 400px; object-fit: cover;"
                 alt="<?php echo CHtml::encode($model->name); ?>">
        <?php else: ?>
            <div class="bg-light border rounded p-5 text-center text-muted">No Image Available</div>
        <?php endif; ?>
    </div>

    <!-- RIGHT: Info + Cart -->
    <div class="col-md-6">
        <h2 class="fw-bold"><?php echo CHtml::encode($model->name); ?></h2>
        <h3 class="text-danger fw-bold">₱<?php echo number_format($model->price, 2); ?></h3>

        <?php if ($isBuyer): ?>
            <form method="post" action="<?php echo Yii::app()->createUrl('cart/add'); ?>">
                <input type="hidden" name="product_id" value="<?php echo $model->id; ?>" />
                <input type="hidden" id="redirect_to_checkout" name="redirect" value="0" />

                <!-- Quantity Selector -->
                <div class="mb-3">
                    <label for="qty" class="form-label fw-semibold">Quantity</label>
                    <div class="d-flex align-items-center gap-2">
                        <div class="input-group" style="width: 120px;">
                            <button type="button" class="btn btn-outline-secondary py-0" onclick="decreaseQty()">−</button>
                            <input type="number" id="qty" name="quantity" value="1" min="1" max="<?php echo $model->stock; ?>" class="form-control text-center p-0" style="font-size: 0.9rem;">
                            <button type="button" class="btn btn-outline-secondary py-0" onclick="increaseQty()">+</button>
                        </div>
                        <span class="text-muted small"><?php echo $model->stock; ?> pieces available</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-3 mb-4">
                    <button type="submit" class="btn btn-outline-danger">
                        🛒 Add to Cart
                    </button>
                    <button type="submit" class="btn btn-danger" onclick="triggerBuyNow()">Buy Now</button>
                </div>
            </form>

        <?php elseif ($isGuest): ?>
            <div class="alert alert-info mb-3">
                Please <a href="<?php echo $loginUrl; ?>">log in</a> to add items to your cart or checkout.
            </div>
            <div class="d-flex gap-3 mb-4">
                <a href="<?php echo $loginUrl; ?>" class="btn btn-outline-danger">🛒 Add to Cart</a>
                <a href="<?php echo $loginUrl; ?>" class="btn btn-danger">Buy Now</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Seller Info -->
<?php if ($model->seller): ?>
    <div class="border-top pt-4 mt-5 mb-4">
        <h5 class="fw-bold">Sold by:</h5>
        <p><?php echo CHtml::encode($model->seller->full_name); ?></p>
    </div>
<?php endif; ?>

<!-- Description -->
<div class="border-top pt-4 mt-4">
    <h5 class="fw-bold mb-3">Product Details</h5>
    <p><?php echo nl2br(CHtml::encode($model->description)); ?></p>
</div>

<script>
function increaseQty() {
    const qtyInput = document.getElementById('qty');
    const max = parseInt(qtyInput.max);
    let val = parseInt(qtyInput.value);
    if (val < max) qtyInput.value = val + 1;
}

function decreaseQty() {
    const qtyInput = document.getElementById('qty');
    let val = parseInt(qtyInput.value);
    if (val > 1) qtyInput.value = val - 1;
}

function triggerBuyNow() {
    document.getElementById('redirect_to_checkout').value = 1;
}
</script>

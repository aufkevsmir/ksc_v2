<?php
$image = $data->image_url ?: Yii::app()->baseUrl . '/images/no-image.png';
?>

<div class="col">
    <div class="card h-100 shadow-sm">
        <img src="<?php echo CHtml::encode($image); ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?php echo CHtml::encode($data->name); ?>">

        <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo CHtml::encode($data->name); ?></h5>
            <p class="text-muted small mb-3">
                Stock: <?php echo $data->stock; ?> |
                ₱<?php echo number_format($data->price, 2); ?>
            </p>

            <div class="mt-auto d-flex gap-2">
                <a href="<?php echo Yii::app()->createUrl('products/update', ['id' => $data->id]); ?>" class="btn btn-sm btn-outline-primary w-50">
                    Edit
                </a>
                <?php echo CHtml::link(
                    'Delete',
                    ['products/delete', 'id' => $data->id],
                    [
                        'class' => 'btn btn-sm btn-outline-danger w-50',
                        'confirm' => 'Are you sure you want to delete this product?',
                        'submit' => ['products/delete', 'id' => $data->id],
                    ]
                ); ?>
            </div>
        </div>
    </div>
</div>

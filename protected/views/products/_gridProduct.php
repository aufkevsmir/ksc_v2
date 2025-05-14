<?php
/* @var $data Products */
$image = $data->image_url ?: Yii::app()->baseUrl . '/images/no-image.png';
?>

<div class="col">
    <div class="card h-100 shadow-sm">
        <img src="<?php echo CHtml::encode($image); ?>" class="card-img-top" alt="<?php echo CHtml::encode($data->name); ?>" style="object-fit: cover; height: 200px;">
        <div class="card-body d-flex flex-column justify-content-between">
            <h5 class="card-title"><?php echo CHtml::encode($data->name); ?></h5>
            <p class="card-text text-danger fw-bold mb-1">₱<?php echo number_format($data->price, 2); ?></p>
            <p class="card-text text-muted small">Stock: <?php echo $data->stock; ?></p>
            <a href="<?php echo Yii::app()->createUrl('products/view', ['id' => $data->id]); ?>" class="btn btn-sm btn-ksc w-100 mt-auto">View Details</a>
        </div>
    </div>
</div>

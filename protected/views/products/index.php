<?php
/* @var $this ProductsController */
/* @var $dataProvider CActiveDataProvider */




?>

<div class="card shadow-sm mb-5">
    <div class="card-body">
        <h3 class="mb-2">All Products</h3>
        <p class="text-muted mb-4">Browse our full product selection. Only active items are listed.</p>

        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_gridProduct', // new cleaner card layout
            'summaryText' => '',
            'emptyText' => '<div class="alert alert-warning w-100">No products available at this time.</div>',
            'itemsCssClass' => 'row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4',
        )); ?>
    </div>
</div>

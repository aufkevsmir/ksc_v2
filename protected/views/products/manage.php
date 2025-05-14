<div class="card shadow-sm mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">My Products</h4>
            <a href="<?php echo Yii::app()->createUrl('products/create'); ?>" class="btn btn-ksc">
                <i class="bi bi-plus-circle"></i> Add New Product
            </a>
        </div>

        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_productItem', // your partial
            'summaryText' => '',
            'emptyText' => '<div class="alert alert-info">You have not added any products yet.</div>',
            'itemsCssClass' => 'row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4',
        )); ?>
    </div>
</div>

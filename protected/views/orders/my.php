<?php
$this->breadcrumbs = ['My Orders'];
?>

<div class="card shadow-sm mb-5">
    <div class="card-body">
        <h4 class="mb-2">My Orders</h4>
        <p class="text-muted mb-4">Here are all your recent orders and their current statuses.</p>

        <?php if (Yii::app()->user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?php echo Yii::app()->user->getFlash('success'); ?>
            </div>
        <?php endif; ?>

        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_orderCard',
            'summaryText' => '',
            'emptyText' => '<div class="alert alert-info">You haven’t placed any orders yet.</div>',
            'itemsCssClass' => 'list-group',
        )); ?>
    </div>
</div>

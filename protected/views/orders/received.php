<?php
$this->breadcrumbs = ['Orders Received'];
?>

<div class="card shadow-sm mb-5">
    <div class="card-body">
        <h4 class="mb-1">Orders You've Received</h4>
        <p class="text-muted mb-4">Here are all the orders placed by buyers for your products.</p>

        <?php $this->widget('zii.widgets.CListView', array(
            'dataProvider' => $dataProvider,
            'itemView' => '_receivedOrderCard', // renders each order box
            'summaryText' => '',
            'emptyText' => '<div class="alert alert-info">No orders received yet.</div>',
            'itemsCssClass' => 'list-group',
        )); ?>
    </div>
</div>

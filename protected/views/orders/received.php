<?php
// $this->breadcrumbs = ['Received Orders'];
?>

<h3>Orders Awaiting Approval</h3>

<?php $this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView' => '_orderCard', // You'll define this below
    'summaryText' => '',
    'emptyText' => 'No paid orders found.',
]); ?>

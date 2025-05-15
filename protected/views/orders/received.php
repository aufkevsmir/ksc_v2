<?php
// Breadcrumbs can be uncommented and customized if needed
// $this->breadcrumbs = ['Received Orders'];
?>

<h3>Orders Awaiting Approval</h3>

<?php
$this->widget('zii.widgets.CListView', [
    'dataProvider' => $dataProvider,
    'itemView'     => '_orderCard', // Partial view for individual orders
    'summaryText'  => '',           // Hides "Showing X of Y" summary
    'emptyText'    => 'No paid orders found.',
    'pager'        => [             // Optional: custom pagination styling
        'header' => '',
        'htmlOptions' => ['class' => 'pagination'],
    ],
]);
?>

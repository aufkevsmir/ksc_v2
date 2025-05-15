<?php
$this->breadcrumbs = [
    'Orders' => ['index'],
    'Order #' . $model->id,
];

$this->renderPartial('_orderDetails', [
    'model' => $model,
    'showActions' => true,
]);
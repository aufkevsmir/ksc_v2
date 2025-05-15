<?php
/* @var $this OrdersController */
/* @var $model Orders */

$this->breadcrumbs = ['Orders' => ['index'], 'Order #' . $model->id];

$this->renderPartial('_orderDetails', [
    'model' => $model,
    'showActions' => true, // controls whether buttons (Pay / Approve) are shown
]);
?>

<?php
class ApiController extends Controller
{
    public function actionCreateDispatch()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $expectedToken = 'DISPATCH_SECRET_123'; // Match with Zapier
        if (!isset($data['token']) || $data['token'] !== $expectedToken) {
            throw new CHttpException(403, 'Unauthorized request.');
        }

        $orderId = $data['order_id'];
        $order = Orders::model()->findByPk($orderId);

        if (!$order) {
            throw new CHttpException(404, 'Order not found.');
        }

        $dispatch = new DispatchSlips();
        $dispatch->order_id = $orderId;
        $dispatch->status = 'pending';
        $dispatch->created_at = new CDbExpression('NOW()');

        if ($dispatch->save()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'errors' => $dispatch->getErrors()]);
        }

        Yii::app()->end();
    }

    public function actionMarkShipped()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $expectedToken = 'SHIP_SECRET_456';

        if (!isset($data['token']) || $data['token'] !== $expectedToken) {
            throw new CHttpException(403, 'Invalid token.');
        }

        $order = Orders::model()->findByPk($data['order_id']);
        if (!$order || $order->status !== 'paid') {
            throw new CHttpException(400, 'Invalid order or state.');
        }

        $order->status = 'shipped';
        $order->save(false);

        echo json_encode(['success' => true]);
        Yii::app()->end();
    }

}

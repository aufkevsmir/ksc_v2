<?php

require_once(dirname(__FILE__) . '/../../vendor/autoload.php');

class StripeController extends Controller
{
    public function actionCheckout($orderId)
    {
        $order = Orders::model()->with('orderItems.product')->findByPk($orderId);

        if (!$order || $order->status !== 'pending') {
            throw new CHttpException(404, 'Order not found or already paid.');
        }

        \Stripe\Stripe::setApiKey(Yii::app()->params['stripe.secretKey']);

        $items = [];
        foreach ($order->orderItems as $item) {
            $items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item->product->name],
                    'unit_amount' => $item->price * 100,
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $items,
            'mode' => 'payment',
            'shipping_address_collection' => [
                'allowed_countries' => ['PH'], // adjust as needed
            ],
            'success_url' => Yii::app()->createAbsoluteUrl('stripe/success', ['orderId' => $orderId]),
            'cancel_url' => Yii::app()->createAbsoluteUrl('cart/index'),
        ]);

        // Store or update the transaction
        $txn = Transactions::model()->findByAttributes(['order_id' => $orderId]);
        if (!$txn) {
            $txn = new Transactions();
            $txn->order_id = $orderId;
        }
        $txn->stripe_session_id = $session->id;
        $txn->amount = $order->total_amount;
        $txn->save();

        $this->redirect($session->url);
    }

    public function actionSuccess($orderId)
    {
        \Stripe\Stripe::setApiKey(Yii::app()->params['stripe.secretKey']);

        $txn = Transactions::model()->findByAttributes(['order_id' => $orderId]);
        if (!$txn) {
            throw new CHttpException(404, 'Transaction not found.');
        }

        $session = \Stripe\Checkout\Session::retrieve([
            'id' => $txn->stripe_session_id,
            'expand' => ['payment_intent', 'shipping'],
        ]);

        $intent = $session->payment_intent;

        if (!$intent || $intent->status !== 'succeeded') {
            Yii::app()->user->setFlash('error', 'Your payment session has expired or was incomplete. Please try again.');
            $this->redirect(['stripe/checkout', 'orderId' => $orderId]);
            return;
        }

        // ✅ Save payment confirmation
        $txn->stripe_payment_intent = $intent->id;
        $txn->paid_at = date('Y-m-d H:i:s');
        $txn->save();

        // ✅ Update order
        $order = Orders::model()->with('orderItems.product', 'buyer')->findByPk($orderId);
        $order->status = 'paid';

        // ✅ Save shipping address if available
        if (!empty($session->shipping) && isset($session->shipping->address)) {
        $addr = $session->shipping->address;
        $name = $session->shipping->name ?? '';
        $fullAddress = implode(', ', array_filter([
            $name,
            $addr->line1 ?? '',
            $addr->line2 ?? '',
            $addr->city ?? '',
            $addr->postal_code ?? '',
            $addr->country ?? ''
        ]));
        $order->shipping_address = $fullAddress;
    }


        $order->save(false);

        // ✅ Deduct stock
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            if ($product) {
                $product->stock = max(0, $product->stock - $item->quantity);
                $product->save(false);
            }
        }

        // ✅ Send to Zapier
        $payload = [
            'order_id'          => $order->id,
            'buyer_name'        => $order->buyer->full_name,
            'buyer_email'       => $order->buyer->email,
            'total_amount'      => $order->total_amount,
            'status'            => 'paid',
            'created_at'        => $order->created_at,
            'shipping_address'  => $order->shipping_address,
            'products' => array_map(function ($item) {
                return [
                    'name'     => $item->product->name,
                    'price'    => $item->price,
                    'quantity' => $item->quantity,
                ];
            }, $order->orderItems),
        ];

        $ch = curl_init('https://hooks.zapier.com/hooks/catch/22896966/2nxpkub/');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        ]);
        curl_exec($ch);
        curl_close($ch);

        // ✅ Redirect
        $this->redirect(['orders/view', 'id' => $orderId]);
    }
}

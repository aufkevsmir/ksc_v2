<?php

class CheckoutController extends Controller
{
    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'place'],
                'expression' => 'Yii::app()->user->getState("role") === "buyer"',
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    /**
     * Confirmation screen (if not using Stripe redirect)
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * Converts cart into one or more seller orders, then redirects to Stripe
     */
    public function actionPlace()
    {
        $userId = Yii::app()->user->id;

        // 1. Load cart items with product info
        $cartItems = Cart::model()->with('product')->findAllByAttributes(['user_id' => $userId]);

        if (empty($cartItems)) {
            Yii::app()->user->setFlash('error', 'Your cart is empty.');
            return $this->redirect(['cart/index']);
        }

        // 2. Group cart items by seller_id (Shopee-style)
        $grouped = [];
        foreach ($cartItems as $item) {
            $grouped[$item->product->seller_id][] = $item;
        }

        $transaction = Yii::app()->db->beginTransaction();

        try {
            $firstOrder = null;

            foreach ($grouped as $sellerId => $items) {
                $order = new Orders;
                $order->buyer_id = $userId;
                $order->seller_id = $sellerId;
                $order->status = 'pending';
                $order->total_amount = 0;
                $order->save(false);

                foreach ($items as $cart) {
                    $orderItem = new OrderItems;
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $cart->product_id;
                    $orderItem->quantity = $cart->quantity;
                    $orderItem->price = $cart->product->price;
                    $orderItem->save(false);

                    $order->total_amount += $orderItem->quantity * $orderItem->price;
                }

                $order->save(false); // update total

                // Save first order for Stripe redirect (assumes one seller for now)
                if ($firstOrder === null) {
                    $firstOrder = $order;
                }
            }

            // 3. Clear buyer's cart
            Cart::model()->deleteAllByAttributes(['user_id' => $userId]);

            $transaction->commit();

            // ✅ Redirect to Stripe Checkout for first order
            $this->redirect(['stripe/checkout', 'orderId' => $firstOrder->id]);

        } catch (Exception $e) {
            $transaction->rollback();
            Yii::app()->user->setFlash('error', 'Checkout failed. Please try again.');
            $this->redirect(['cart/index']);
        }
    }
}

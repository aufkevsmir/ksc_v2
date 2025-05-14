<?php

class CartController extends Controller
{
    public function filters()
    {
        return ['accessControl'];
    }

    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['index', 'add', 'remove', 'clear'],
                'expression' => 'Yii::app()->user->getState("role") === "buyer"',
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    /**
     * Displays the cart for the logged-in buyer
     */
    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'user_id = :uid';
        $criteria->params = [':uid' => Yii::app()->user->id];
        $criteria->with = ['product'];

        $cartItems = Cart::model()->findAll($criteria);

        $this->render('index', ['cartItems' => $cartItems]);
    }

    /**
     * Adds a product to the buyer's cart.
     * Supports both add-to-cart and buy-now redirect to checkout.
     */
    public function actionAdd()
    {
        if (!Yii::app()->request->isPostRequest) {
            throw new CHttpException(400, 'Invalid request method.');
        }

        $userId = Yii::app()->user->id;
        $productId = Yii::app()->request->getPost('product_id');
        $quantity = (int) Yii::app()->request->getPost('quantity', 1);
        $redirectToCheckout = Yii::app()->request->getPost('redirect') == 1;

        if (!$productId || $quantity < 1) {
            Yii::app()->user->setFlash('error', 'Invalid product or quantity.');
            $this->redirect(['index']);
        }

        $cart = Cart::model()->findByAttributes([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        if ($cart) {
            $cart->quantity += $quantity;
        } else {
            $cart = new Cart;
            $cart->user_id = $userId;
            $cart->product_id = $productId;
            $cart->quantity = $quantity;
        }

        if ($cart->save()) {
            Yii::app()->user->setFlash('success', 'Product added to cart.');
        } else {
            Yii::app()->user->setFlash('error', 'Unable to add product.');
        }

        // Redirect accordingly
        $this->redirect($redirectToCheckout ? ['checkout/place'] : ['index']);
    }

    /**
     * Removes a product from the cart
     * @param int $id cart row ID
     */
    public function actionRemove($id)
    {
        $cart = Cart::model()->findByPk($id);

        if ($cart && $cart->user_id == Yii::app()->user->id) {
            $cart->delete();
            Yii::app()->user->setFlash('info', 'Item removed.');
        } else {
            Yii::app()->user->setFlash('error', 'Item not found or unauthorized.');
        }

        $this->redirect(['index']);
    }

    /**
 * Clears all cart items for the current buyer
 */
public function actionClear()
{
    $userId = Yii::app()->user->id;

    Cart::model()->deleteAllByAttributes([
        'user_id' => $userId,
    ]);

    Yii::app()->user->setFlash('info', 'Your cart has been cleared.');
    $this->redirect(['index']);
}

}

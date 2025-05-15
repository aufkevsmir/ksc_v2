<?php

class OrdersController extends Controller
{
    public $layout = '//layouts/column2';

    // --- Filters ---
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    // --- Access Rules ---
    public function accessRules()
    {
        return [
            ['allow',
                'actions' => ['index', 'view'],
                'users' => ['*'],
            ],
			['allow',
				'actions' => ['print'],
				'expression' => 'in_array(Yii::app()->user->getState("role"), ["buyer", "seller", "admin"])',
			],

            ['allow',
                'actions' => ['my'],
                'expression' => 'Yii::app()->user->getState("role") === "buyer"',
            ],
            ['allow',
                'actions' => ['received', 'approve'],
                'expression' => 'in_array(Yii::app()->user->getState("role"), ["seller", "admin"])',
            ],
            ['allow',
                'actions' => ['admin', 'delete'],
                'expression' => 'Yii::app()->user->getState("role") === "admin"',
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    // --- CRUD Actions ---

    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Orders');
        $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionView($id)
    {
        $this->render('view', ['model' => $this->loadModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Orders;

        if (isset($_POST['Orders'])) {
            $model->attributes = $_POST['Orders'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if (isset($_POST['Orders'])) {
            $model->attributes = $_POST['Orders'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax'])) {
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['admin']);
        }
    }

    public function actionAdmin()
    {
        $model = new Orders('search');
        $model->unsetAttributes();

        if (isset($_GET['Orders'])) {
            $model->attributes = $_GET['Orders'];
        }

        $this->render('admin', ['model' => $model]);
    }

    // --- Buyer & Seller Actions ---

    public function actionMy()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'buyer_id = :uid';
        $criteria->params = [':uid' => Yii::app()->user->id];
        $criteria->order = 'created_at DESC';

        $dataProvider = new CActiveDataProvider('Orders', [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->render('my', ['dataProvider' => $dataProvider]);
    }

    public function actionReceived()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'seller_id = :sid AND status = "paid"';
        $criteria->params = [':sid' => Yii::app()->user->id];
        $criteria->order = 'created_at DESC';

        $dataProvider = new CActiveDataProvider('Orders', [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->render('received', ['dataProvider' => $dataProvider]);
    }

    public function actionApprove($id)
    {
        $order = Orders::model()->with('buyer', 'orderItems.product')->findByPk($id);

        if (!$order || $order->status !== 'paid') {
            throw new CHttpException(400, 'Order cannot be approved.');
        }

        if ($order->seller_id != Yii::app()->user->id) {
            throw new CHttpException(403, 'Not authorized.');
        }

        $order->status = 'shipped';
        $order->save(false);

        // Notify via Zapier Webhook
        $payload = [
            'order_id'     => $order->id,
            'buyer_email'  => $order->buyer->email,
            'buyer_name'   => $order->buyer->full_name,
            'total_amount' => $order->total_amount,
            'products'     => array_map(function ($item) {
                return [
                    'name'     => $item->product->name,
                    'quantity' => $item->quantity,
                    'price'    => $item->price,
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

        Yii::app()->user->setFlash('success', 'Order approved and dispatch slip sent.');
        $this->redirect(['view', 'id' => $order->id]);
    }

    public function actionPrint($id)
    {
        $model = $this->loadModel($id);
        $this->renderPartial('_orderDetails', [
            'model' => $model,
            'showActions' => false,
        ]);
    }

    // --- Utility Functions ---

    protected function loadModel($id)
    {
        $model = Orders::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'orders-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}

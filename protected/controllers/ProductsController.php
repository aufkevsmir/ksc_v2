<?php

class ProductsController extends Controller
{
    public $layout = '//layouts/column2';

    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    public function accessRules()
    {
        return [
            ['allow', // Public
                'actions' => ['index', 'view'],
                'users' => ['*'],
            ],
            ['allow', // Seller and admin
                'actions' => ['create', 'update', 'manage', 'deactivate', 'dashboard', 'delete'],
                'expression' => 'in_array(Yii::app()->user->getState("role"), ["seller", "admin"])',
            ],
            ['allow', // Admin only
                'actions' => ['admin'],
                'expression' => 'Yii::app()->user->getState("role") === "admin"',
            ],
            ['deny', 'users' => ['*']],
        ];
    }

    public function actionView($id)
    {
        $this->render('view', ['model' => $this->loadModel($id)]);
    }

    public function actionCreate()
    {
        $model = new Products;

        if (isset($_POST['Products'])) {
            $model->attributes = $_POST['Products'];
            $model->seller_id = Yii::app()->user->id;
            $model->status = 'active';

            // Handle image upload
            $uploadedFile = CUploadedFile::getInstance($model, 'image_url');
            if ($uploadedFile) {
                $filename = uniqid() . '_' . $uploadedFile->name;
                $model->image_url = 'uploads/products/' . $filename;
            }

            if ($model->save()) {
                if ($uploadedFile) {
                    $uploadPath = Yii::getPathOfAlias('webroot') . '/uploads/products/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0775, true);
                    }
                    $uploadedFile->saveAs($uploadPath . $filename);
                }

                Yii::app()->user->setFlash('success', 'Product created successfully.');
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        if ($model->seller_id !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Unauthorized access.');
        }

        if (isset($_POST['Products'])) {
            $model->attributes = $_POST['Products'];

            // Handle image upload
            $uploadedFile = CUploadedFile::getInstance($model, 'image_url');
            if ($uploadedFile) {
                $filename = uniqid() . '_' . $uploadedFile->name;
                $model->image_url = 'uploads/products/' . $filename;
            }

            if ($model->save()) {
                if ($uploadedFile) {
                    $uploadPath = Yii::getPathOfAlias('webroot') . '/uploads/products/';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0775, true);
                    }
                    $uploadedFile->saveAs($uploadPath . $filename);
                }

                Yii::app()->user->setFlash('success', 'Product updated successfully.');
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $product = $this->loadModel($id);
        $isOwner = $product->seller_id == Yii::app()->user->id;
        $isAdmin = Yii::app()->user->getState("role") === "admin";

        if (!($isOwner || $isAdmin) || $product->status !== 'active') {
            throw new CHttpException(403, 'Unauthorized or product already deleted.');
        }

        $product->status = 'inactive';
        $product->save(false);

        if (!Yii::app()->request->isAjaxRequest) {
            Yii::app()->user->setFlash('success', 'Product deleted successfully.');
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : ['products/manage']);
        }
    }

    public function actionIndex()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'status = "active"';

        $dataProvider = new CActiveDataProvider('Products', ['criteria' => $criteria]);

        $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionAdmin()
    {
        $model = new Products('search');
        $model->unsetAttributes();

        if (isset($_GET['Products'])) {
            $model->attributes = $_GET['Products'];
        }

        $this->render('admin', ['model' => $model]);
    }

    public function actionManage()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'seller_id = :seller_id AND status = "active"';
        $criteria->params = [':seller_id' => Yii::app()->user->id];

        $dataProvider = new CActiveDataProvider('Products', [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->render('manage', ['dataProvider' => $dataProvider]);
    }

    public function actionDeactivate($id)
    {
        $product = $this->loadModel($id);

        if ($product->seller_id !== Yii::app()->user->id) {
            throw new CHttpException(403, 'Unauthorized.');
        }

        $product->status = 'inactive';

        if ($product->save()) {
            Yii::app()->user->setFlash('success', 'Product deactivated.');
        }

        $this->redirect(['manage']);
    }

    public function actionDashboard()
    {
        $role = Yii::app()->user->getState('role');
        if (!in_array($role, ['seller', 'admin'])) {
            throw new CHttpException(403, 'Access denied.');
        }

        $sellerId = Yii::app()->user->id;

        $productCount = Products::model()->countByAttributes(['seller_id' => $sellerId, 'status' => 'active']);
        $orderCount = Orders::model()->countByAttributes(['seller_id' => $sellerId]);

        $revenue = Yii::app()->db->createCommand()
            ->select('SUM(total_amount) as total')
            ->from('orders')
            ->where('seller_id = :sid AND status IN ("paid", "shipped", "completed")', [':sid' => $sellerId])
            ->queryScalar();

        $itemCount = Yii::app()->db->createCommand()
            ->select('SUM(oi.quantity) as total')
            ->from('order_items oi')
            ->join('orders o', 'oi.order_id = o.id')
            ->where('o.seller_id = :sid AND o.status IN ("paid", "shipped", "completed")', [':sid' => $sellerId])
            ->queryScalar();

        $this->render('dashboard', [
            'productCount' => $productCount,
            'orderCount' => $orderCount,
            'revenue' => $revenue ?: 0,
            'itemCount' => $itemCount ?: 0,
        ]);
    }

    public function loadModel($id)
    {
        $model = Products::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'The requested product does not exist.');
        }
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'products-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}

<?php

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ],
            'page' => [
                'class' => 'CViewAction',
            ],
        ];
    }

    /**
     * Homepage
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * Error handling
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    /**
     * Contact form
     */
    public function actionContact()
    {
        $model = new ContactForm;

        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                           "Reply-To: {$model->email}\r\n" .
                           "MIME-Version: 1.0\r\n" .
                           "Content-Type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }

        $this->render('contact', ['model' => $model]);
    }

    /**
     * Login
     */
    public function actionLogin()
    {
        $model = new LoginForm;

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login()) {
                // Role-based redirect
                $role = Yii::app()->user->getState('role');

                switch ($role) {
                    case 'admin':
                        $this->redirect(['users/admin']);
                        break;
                    case 'seller':
                        $this->redirect(['products/dashboard']);
                        break;
                    case 'buyer':
                    default:
                        $this->redirect(Yii::app()->homeUrl);
                }
            }
        }

        $this->render('login', ['model' => $model]);
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        Yii::app()->user->setFlash('info', 'You have been logged out.');
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * Registration
     */
    public function actionRegister()
    {
        $model = new RegisterForm;

        if (isset($_POST['RegisterForm'])) {
            $model->attributes = $_POST['RegisterForm'];

            if ($model->validate()) {
                $user = new Users;
                $user->email = $model->email;
                $user->full_name = $model->full_name;
                $user->role = $model->role ?? 'buyer';
                $user->setPassword($model->password);

                if ($user->save()) {
                    Yii::app()->user->setFlash('success', 'Registration successful. Please log in.');
                    $this->redirect(['site/login']);
                }
            }
        }

        $this->render('register', ['model' => $model]);
    }
}

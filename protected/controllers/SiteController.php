<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * Default homepage action
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * Handles external exceptions
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
     * Contact page
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
     * Login page
     */
    public function actionLogin()
	{
		$model = new LoginForm;

		if (isset($_POST['LoginForm'])) {
			$model->attributes = $_POST['LoginForm'];
			if ($model->validate() && $model->login()) {
    		$this->redirect(Yii::app()->homeUrl);
				// Role-based redirect
				$role = Yii::app()->user->getState('role');
				switch ($role) {
					case 'admin':
						$this->redirect(['users/admin']);
						break;
					case 'seller':
						$this->redirect(['products/index']);
						break;
					default:
						$this->redirect(Yii::app()->user->returnUrl);
				}
			}
		}

		$this->render('login', ['model' => $model]);
	}


    /**
     * Logs out the current user and redirects to homepage
     */
    public function actionLogout()
    {
        Yii::app()->user->logout();
        Yii::app()->user->setFlash('info', 'You have been logged out.');
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * User registration
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

<?php

/**
 * LoginForm class.
 * Used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
    public $email;
    public $password;
    public $rememberMe;

    private $_identity;

    /**
     * Validation rules.
     */
    public function rules()
    {
        return array(
            array('email, password', 'required'),
            array('email', 'email'),
            array('rememberMe', 'boolean'),
            array('password', 'authenticate'),
        );
    }

    /**
     * Attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'email' => 'Email Address',
            'password' => 'Password',
            'rememberMe' => 'Remember me next time',
        );
    }

    /**
     * Authenticates the password.
     */
    public function authenticate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            if (!$this->_identity->authenticate()) {
                $this->addError('password', 'Incorrect email or password.');
            }
        }
    }

    /**
     * Logs in the user using the identity object.
     * @return bool whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        }

        return false;
    }
}

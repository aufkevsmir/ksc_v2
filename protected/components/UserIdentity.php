<?php

/**
 * UserIdentity handles user authentication via email.
 * Uses salted password verification and stores user info in session.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    /**
     * Authenticates a user using their email and password.
     * @return bool whether authentication succeeds
     */
    public function authenticate()
    {
        $user = Users::model()->findByAttributes(['email' => $this->username]);

        if (!$user) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } elseif (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $user->id;

            // Set session states
            Yii::app()->user->setState('role', $user->role);
            Yii::app()->user->setState('full_name', $user->full_name);
            Yii::app()->user->setState('email', $user->email);

            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    /**
     * Returns the ID of the authenticated user.
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }
}

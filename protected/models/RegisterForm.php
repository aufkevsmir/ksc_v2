<?php
class RegisterForm extends CFormModel
{
    public $email;
    public $password;
    public $full_name;
    public $role;

    public function rules()
    {
        return array(
            array('email, password, full_name', 'required'),
            array('email', 'email'),
            array('email', 'unique', 'className' => 'Users', 'attributeName' => 'email'),
            array('role', 'in', 'range' => ['buyer', 'seller', 'admin']),
        );
    }
}

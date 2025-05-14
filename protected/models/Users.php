<?php

/**
 * This is the model class for table "users".
 *
 * Columns:
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $salt
 * @property string $full_name
 * @property string $role
 * @property string $created_at
 *
 * Relations:
 * @property Cart[] $carts
 * @property Inquiries[] $inquiries
 * @property Orders[] $orders (as buyer)
 * @property Orders[] $orders1 (as seller)
 * @property Products[] $products
 */
class Users extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('email, full_name, role, password_hash, salt', 'required'),
            array('email', 'email'),
            array('email', 'unique'),
            array('role', 'in', 'range' => ['buyer', 'seller', 'admin']),
        );
    }

    /**
     * Sets a hashed password with a secure salt.
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->salt = bin2hex(random_bytes(32));
        $this->password_hash = hash('sha256', $this->salt . $password);
    }

    /**
     * Validates a password using stored salt.
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return $this->password_hash === hash('sha256', $this->salt . $password);
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'carts' => array(self::HAS_MANY, 'Cart', 'user_id'),
            'inquiries' => array(self::HAS_MANY, 'Inquiries', 'buyer_id'),
            'orders' => array(self::HAS_MANY, 'Orders', 'buyer_id'),      // as buyer
            'orders1' => array(self::HAS_MANY, 'Orders', 'seller_id'),    // as seller
            'products' => array(self::HAS_MANY, 'Products', 'seller_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'password_hash' => 'Password Hash',
            'salt' => 'Salt',
            'full_name' => 'Full Name',
            'role' => 'Role',
            'created_at' => 'Created At',
        );
    }

    /**
     * @return CActiveDataProvider for search/filter UI
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('password_hash', $this->password_hash, true);
        $criteria->compare('salt', $this->salt, true);
        $criteria->compare('full_name', $this->full_name, true);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('created_at', $this->created_at, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * @param string $className active record class name
     * @return Users the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    // Role helpers
    public function isBuyer() { return $this->role === 'buyer'; }
    public function isSeller() { return $this->role === 'seller'; }
    public function isAdmin()  { return $this->role === 'admin'; }
}

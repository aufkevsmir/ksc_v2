<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $id
 * @property integer $buyer_id
 * @property integer $seller_id
 * @property string $total_amount
 * @property string $status
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property DispatchSlips[] $dispatchSlips
 * @property OrderItems[] $orderItems
 * @property Users $buyer
 * @property Users $seller
 * @property Transactions[] $transactions
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buyer_id, seller_id, total_amount', 'required'),
			array('buyer_id, seller_id', 'numerical', 'integerOnly'=>true),
			array('total_amount', 'length', 'max'=>10),
			array('status', 'length', 'max'=>9),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, buyer_id, seller_id, total_amount, status, created_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'dispatchSlips' => array(self::HAS_MANY, 'DispatchSlips', 'order_id'),
			'orderItems' => array(self::HAS_MANY, 'OrderItems', 'order_id'),
			'buyer' => array(self::BELONGS_TO, 'Users', 'buyer_id'),
			'seller' => array(self::BELONGS_TO, 'Users', 'seller_id'),
			'transactions' => array(self::HAS_MANY, 'Transactions', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'buyer_id' => 'Buyer',
			'seller_id' => 'Seller',
			'total_amount' => 'Total Amount',
			'status' => 'Status',
			'created_at' => 'Created At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('buyer_id',$this->buyer_id);
		$criteria->compare('seller_id',$this->seller_id);
		$criteria->compare('total_amount',$this->total_amount,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

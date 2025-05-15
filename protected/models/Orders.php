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
	public function tableName()
	{
		return 'orders';
	}

	public function rules()
	{
		return [
			['buyer_id, seller_id, total_amount', 'required'],
			['buyer_id, seller_id', 'numerical', 'integerOnly' => true],
			['total_amount', 'length', 'max' => 10],
			['status', 'length', 'max' => 9],
			['shipping_address', 'length', 'max' => 255],
			['paid_at, created_at', 'safe'],
			['id, buyer_id, seller_id, total_amount, status, shipping_address, paid_at, created_at', 'safe', 'on' => 'search'],
		];
	}

	public function relations()
	{
		return [
			'dispatchSlips' => [self::HAS_MANY, 'DispatchSlips', 'order_id'],
			'orderItems'    => [self::HAS_MANY, 'OrderItems', 'order_id'],
			'buyer'         => [self::BELONGS_TO, 'Users', 'buyer_id'],
			'seller'        => [self::BELONGS_TO, 'Users', 'seller_id'],
			'transactions'  => [self::HAS_MANY, 'Transactions', 'order_id'],
		];
	}

	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'buyer_id'         => 'Buyer',
			'seller_id'        => 'Seller',
			'total_amount'     => 'Total Amount',
			'status'           => 'Status',
			'shipping_address' => 'Shipping Address',
			'paid_at'          => 'Paid At',
			'created_at'       => 'Created At',
		];
	}

	public function search()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('id', $this->id);
		$criteria->compare('buyer_id', $this->buyer_id);
		$criteria->compare('seller_id', $this->seller_id);
		$criteria->compare('total_amount', $this->total_amount, true);
		$criteria->compare('status', $this->status, true);
		$criteria->compare('shipping_address', $this->shipping_address, true);
		$criteria->compare('paid_at', $this->paid_at, true);
		$criteria->compare('created_at', $this->created_at, true);

		return new CActiveDataProvider($this, ['criteria' => $criteria]);
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}


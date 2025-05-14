<?php

/**
 * This is the model class for table "inquiries".
 *
 * The followings are the available columns in table 'inquiries':
 * @property integer $id
 * @property integer $buyer_id
 * @property integer $product_id
 * @property string $message
 * @property string $status
 * @property string $created_at
 *
 * The followings are the available model relations:
 * @property Users $buyer
 * @property Products $product
 */
class Inquiries extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'inquiries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buyer_id, product_id', 'required'),
			array('buyer_id, product_id', 'numerical', 'integerOnly'=>true),
			array('status', 'length', 'max'=>9),
			array('message, created_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, buyer_id, product_id, message, status, created_at', 'safe', 'on'=>'search'),
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
			'buyer' => array(self::BELONGS_TO, 'Users', 'buyer_id'),
			'product' => array(self::BELONGS_TO, 'Products', 'product_id'),
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
			'product_id' => 'Product',
			'message' => 'Message',
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
		$criteria->compare('product_id',$this->product_id);
		$criteria->compare('message',$this->message,true);
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
	 * @return Inquiries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

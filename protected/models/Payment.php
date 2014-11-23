<?php

class Payment extends CActiveRecord  
{
	const INCOME = 1;
	const EXPEND = 2;
	
	protected static $types;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{payment}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'amount' => Yii::t('payment', 'Amount'),
			'created_by_id' => Yii::t('payment', 'Created by'),
			'created_by' => Yii::t('payment', 'Created by'),
			'date_created' => Yii::t('payment', 'Date Created'),
			'description' => Yii::t('payment', 'Description'),
			'id' => Yii::t('payment', 'Number'),
			'invoice_id' => Yii::t('payment', 'Invoice'),
			'invoice' => Yii::t('payment', 'Invoice'),
			'invoice.from' => Yii::t('payment', 'Payee'),
			'invoice.to' => Yii::t('payment', 'Payer'),
			'type' => Yii::t('payment', 'Type'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	amount', 
					'numerical', 'on' => 'create, update'),
			array('	amount,
					type', 
					'required', 'on' => 'create, update'),
			array('	description', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	type', 
					'in', 'range' => array(1, 2), 'on' => 'create, update'),
			array('	invoice_id,
					type', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'invoice' => array(self::BELONGS_TO, 'Invoice', 'invoice_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'StampBehavior',
				'create_time_attribute' => 'date_created',
				'created_by_attribute' => 'created_by',
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'created_by',
					'invoice',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'payment';
		$criteria->compare('payment.type', $this->type);
		$criteria->compare('payment.invoice_id', $this->invoice_id);
		$criteria->with = array(
			'invoice' => array(
				'with' => array('from', 'to'),
			),
		);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'payment.date_created DESC',
				'attributes' => array(
					'invoice' => array(
						'asc' => 'invoice.id',
						'desc' => 'invoice.id DESC',
					),
					'invoice.from' => array(
						'asc' => 'from.real_name, from.username',
						'desc' => 'from.real_name DESC, from.username DESC',
					),
					'invoice.to' => array(
						'asc' => 'to.real_name, to.username',
						'desc' => 'to.real_name DESC, to.username DESC',
					),
					'*',
				),
			),
		));
	}
	
	public function getNumber()
	{
		return sprintf('#%05d', $this->id);
	}
	
	public function __toString()
	{
		return $this->getNumber();
	}
	
	
	public static function getListTypes()
	{
		if (null === self::$types) {
			self::$types = array(
				self::INCOME => Yii::t('payment', 'Income'),
				self::EXPEND => Yii::t('payment', 'Expend'),
			);
		}
		return self::$types;
	}
	
	public function getType()
	{
		return array_key_exists($this->type, self::getListTypes()) ? self::$types[$this->type] : '';
	}
}

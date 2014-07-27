<?php

class InvoiceItem extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{invoice_item}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'hours' => Yii::t('invoiceItem', 'Hours'),
			'invoice_id' => Yii::t('invoiceItem', 'Invoice'),
			'invoice' => Yii::t('invoiceItem', 'Invoice'),
			'name' => Yii::t('invoiceItem', 'Name'),
			'task_id' => Yii::t('invoiceItem', 'Task'),
			'task' => Yii::t('invoiceItem', 'Task'),
			'value' => Yii::t('invoiceItem', 'Value'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	hours,
					value', 
					'numerical'),
			array('	name', 
					'length', 'max' => 200),
			array('	name,
					value', 
					'required'),
		);
	}
	
	public function relations()
	{
		return array(
			'invoice' => array(self::BELONGS_TO, 'Invoice', 'invoice_id'),
			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'invoice',
					'task',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
	
	public function __toString()
	{
		return $this->getDisplayName();
	}
	
	public function getDisplayName()
	{
		return $this->name;
	}
	
	public static function getList()
	{
		$criteria = new CDbCriteria();
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'displayName');
	}
}

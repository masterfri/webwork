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
			array(' formattedHours',
					'safe'),
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
		$criteria->alias = 'invoiceitem';
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'invoiceitem.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public function getFormattedHours()
	{
		return Yii::app()->format->formatHours($this->hours);
	}
	
	public function setFormattedHours($value)
	{
		$this->hours = Yii::app()->format->parseHours($value);
	}
}

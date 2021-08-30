<?php

class CompletedJob extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{completed_jobs}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'name' => Yii::t('completedJob', 'Name'),
			'qty' => Yii::t('completedJob', 'Qty'),
			'price' => Yii::t('completedJob', 'Price'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	price', 
					'numerical', 'min' => 0, 'on' => 'create, update'),
			array('	qty', 
					'numerical', 'min' => 1, 'integerOnly' => true, 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name,
					price,
					qty', 
					'required'),
		);
	}
	
	public function relations()
	{
		return array(
			'report' => array(self::BELONGS_TO, 'CompletionReport', 'report_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'report',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'completed_jobs';
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'completed_jobs.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
}

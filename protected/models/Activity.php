<?php

class Activity extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{activity}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('activity', 'Created by'),
			'created_by' => Yii::t('activity', 'Created by'),
			'description' => Yii::t('activity', 'Description'),
			'name' => Yii::t('activity', 'Name'),
			'rates' => Yii::t('activity', 'Rates'),
			'time_created' => Yii::t('activity', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	description', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name', 
					'required', 'on' => 'create, update'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'rates' => array(self::HAS_MANY, 'ActivityRate', 'activity_id'),
			'rate' => array(self::HAS_ONE, 'ActivityRate', 'activity_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'StampBehavior',
				'create_time_attribute' => 'time_created',
				'created_by_attribute' => 'created_by',
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'created_by',
					'rates' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'activity';
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'activity.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public static function getList($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->order = 'name';
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'name');
	}
}

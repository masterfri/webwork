<?php

class AppEntity extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{app_entity}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by' => Yii::t('appEntity', 'Created by'),
			'description' => Yii::t('appEntity', 'Description'),
			'schemes' => Yii::t('appEntity', 'Schemes'),
			'label' => Yii::t('appEntity', 'Label'),
			'name' => Yii::t('appEntity', 'Name'),
			'time_created' => Yii::t('appEntity', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array(' name,
					schemes',
					'required'),
			array(' name', 
					'unique', 'criteria' => array('condition' => 'application_id = :application_id', 'params' => array(':application_id' => $this->application_id)), 'on' => 'create'),
			array(' name', 
					'unique', 'criteria' => array('condition' => 'id != :id AND application_id = :application_id', 'params' => array(':id' => $this->id, ':application_id' => $this->application_id)), 'on' => 'update'),
			array(' name,
					label', 
					'length', 'max' => 100),
			array(' description,
					json_source', 
					'length', 'max' => 16000),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'application' => array(self::BELONGS_TO, 'Application', 'application_id'),
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
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'appEntity';
		$criteria->compare('appEntity.name', $this->name, true);
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'appEntity.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public function getSchemes()
	{
		return empty($this->json_schemes) ? array() : CJSON::decode($this->json_schemes);
	}
	
	public function setSchemes($value)
	{
		$this->json_schemes = CJSON::encode($value);
	}
}

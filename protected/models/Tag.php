<?php

class Tag extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{tag}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'color' => Yii::t('tag', 'Color'),
			'created_by_id' => Yii::t('tag', 'Created by'),
			'created_by' => Yii::t('tag', 'Created by'),
			'name' => Yii::t('tag', 'Name'),
			'time_created' => Yii::t('tag', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	color', 
					'length', 'max' => 20),
			array('	name', 
					'length', 'max' => 200),
			array('	name', 
					'required'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
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
		$criteria->compare('t.name', $this->name, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public static function getList()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'name';
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'name');
	}
}

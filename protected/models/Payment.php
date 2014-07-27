<?php

class Payment extends CActiveRecord  
{
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
			'project_id' => Yii::t('payment', 'Project'),
			'project' => Yii::t('payment', 'Project'),
			'task_id' => Yii::t('payment', 'Task'),
			'task' => Yii::t('payment', 'Task'),
			'type' => Yii::t('payment', 'Type'),
			'user_id' => Yii::t('payment', 'User'),
			'user' => Yii::t('payment', 'User'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	amount', 
					'numerical'),
			array('	amount,
					type', 
					'required'),
			array('	description', 
					'length', 'max' => 16000),
			array('	project_id,
					task_id,
					user_id', 
					'safe'),
			array('	type', 
					'in', 'range' => array(1, 2)),
			array('	project_id,
					task_id,
					type,
					user_id', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
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
					'project',
					'task',
					'user',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->compare('t.project_id', $this->project_id);
		$criteria->compare('t.task_id', $this->task_id);
		$criteria->compare('t.type', $this->type);
		$criteria->compare('t.user_id', $this->user_id);
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
		return "#".$this->primaryKey;
	}
	
	public static function getList()
	{
		$criteria = new CDbCriteria();
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'displayName');
	}
}

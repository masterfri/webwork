<?php

class TimeEntry extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{time_entry}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'activity_id' => Yii::t('timeEntry', 'Activity'),
			'activity' => Yii::t('timeEntry', 'Activity'),
			'amount' => Yii::t('timeEntry', 'Amount'),
			'created_by_id' => Yii::t('timeEntry', 'Created by'),
			'created_by' => Yii::t('timeEntry', 'Created by'),
			'date_created' => Yii::t('timeEntry', 'Date Created'),
			'description' => Yii::t('timeEntry', 'Description'),
			'project_id' => Yii::t('timeEntry', 'Project'),
			'project' => Yii::t('timeEntry', 'Project'),
			'task_id' => Yii::t('timeEntry', 'Task'),
			'task' => Yii::t('timeEntry', 'Task'),
			'user_id' => Yii::t('timeEntry', 'User'),
			'user' => Yii::t('timeEntry', 'User'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	activity_id,
					amount,
					user_id', 
					'required'),
			array('	amount', 
					'numerical'),
			array('	description', 
					'length', 'max' => 16000),
			array('	project_id,
					task_id', 
					'safe'),
			array('	activity_id,
					project_id,
					task_id,
					user_id', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'activity' => array(self::BELONGS_TO, 'Activity', 'activity_id'),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}
	
	public function scopes()
	{
		return array(
			'my' => array(
				'condition' => 'time_entry.user_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				),
			),
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
					'activity',
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
		$criteria->alias = 'time_entry';
		$criteria->compare('time_entry.activity_id', $this->activity_id);
		$criteria->compare('time_entry.project_id', $this->project_id);
		$criteria->compare('time_entry.task_id', $this->task_id);
		$criteria->compare('time_entry.user_id', $this->user_id);
		$criteria->with = array('project', 'task', 'user', 'activity');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'time_entry.date_created DESC',
				'attributes' => array(
					'project' => array(
						'asc' => 'project.name ASC',
						'desc' => 'project.name DESC',
					),
					'task' => array(
						'asc' => 'task.name ASC',
						'desc' => 'task.name DESC',
					),
					'user' => array(
						'asc' => 'user.real_name ASC',
						'desc' => 'user.real_name DESC',
					),
					'activity' => array(
						'asc' => 'activity.name ASC',
						'desc' => 'activity.name DESC',
					),
					'*',
				)
			),
		));
	}
	
	public function getSum($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->select = new CDbExpression('SUM(time_entry.`amount`)');
		$this->applyScopes($criteria);
		$cb = $this->dbConnection->commandBuilder;
		$command = $cb->createFindCommand($this->tableName(), $criteria);
		return (float) $command->queryScalar();
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
	

<?php

class TimeEntry extends CActiveRecord  
{
	public $milestone_id;
	
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
			'formattedAmount' => Yii::t('timeEntry', 'Amount'),
			'created_by_id' => Yii::t('timeEntry', 'Created by'),
			'created_by' => Yii::t('timeEntry', 'Created by'),
			'date_created' => Yii::t('timeEntry', 'Date Created'),
			'description' => Yii::t('timeEntry', 'Description'),
			'milestone_id' => Yii::t('task', 'Milestone'),
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
			array('	user_id', 
					'required', 'on' => 'create'),
			array('	activity_id,
					formattedAmount', 
					'required', 'on' => 'create, update, report'),
			array('	description', 
					'length', 'max' => 16000, 'on' => 'create, update, report'),
			array('	project_id,
					task_id', 
					'safe', 'on' => 'create'),
			array(' amount',
					'numerical', 'min' => 0, 'on' => 'create, update, report'),
			array('	activity_id,
					date_created,
					milestone_id,
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
		$criteria->compare('task.milestone_id', $this->milestone_id);
		$criteria->compare('time_entry.project_id', $this->project_id);
		$criteria->compare('time_entry.task_id', $this->task_id);
		$criteria->compare('time_entry.user_id', $this->user_id);
		if (!empty($this->date_created)) {
			if (preg_match('/^([0-9]{4})-([0-9]{2})$/', $this->date_created, $ym)) {
				$criteria->compare('YEAR(time_entry.date_created)', $ym[1]);
				$criteria->compare('MONTH(time_entry.date_created)', $ym[2]);
			} else {
				$criteria->compare('DATE(time_entry.date_created)', $this->date_created);
			}
		}
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
	
	public function getFormattedAmount()
	{
		return Yii::app()->format->formatHours($this->amount);
	}
	
	public function setFormattedAmount($value)
	{
		$this->amount = Yii::app()->format->parseHours($value);
	}
	
	public function getActivityLevel($project_id, $user_id = null, $days=10, $skipWeekend=true)
	{
		$activity = array();
		$time = mktime(12, 0, 0);
		for ($i = 0; $i < $days; $i++) {
			if (!$skipWeekend || !WorkingHours::isWeekend($time)) {
				$activity[date('Y-m-d', $time)] = 0;
			}
			$time -= 86400;
		}
		$criteria = new CDbCriteria();
		$criteria->select = 'DATE(date_created) AS date_created, SUM(amount) as amount';
		$criteria->group = 'DATE(date_created)';
		$criteria->addCondition('date_created >= DATE_sub(NOW(), INTERVAL :days DAY)');
		if ($project_id !== null) {
			$criteria->compare('project_id', $project_id);
		}
		if ($user_id !== null) {
			$criteria->compare('user_id', $user_id);
		}
		$criteria->params[':days'] = $days;
		$data = $this->findAll($criteria);
		foreach ($data as $record) {
			$activity[$record->date_created] = $record->amount;
		}
		ksort($activity);
		return $activity;
	}
}

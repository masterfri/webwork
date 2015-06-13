<?php

class Milestone extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{milestone}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'attachments' => Yii::t('milestone', 'Attachments'),
			'created_by_id' => Yii::t('milestone', 'Created by'),
			'created_by' => Yii::t('milestone', 'Created by'),
			'description' => Yii::t('milestone', 'Description'),
			'date_start' => Yii::t('milestone', 'Date Start'),
			'due_date' => Yii::t('milestone', 'Due Date'),
			'name' => Yii::t('milestone', 'Name'),
			'project_id' => Yii::t('milestone', 'Project'),
			'project' => Yii::t('milestone', 'Project'),
			'tasks' => Yii::t('milestone', 'Tasks'),
			'count_tasks' => Yii::t('milestone', 'Tasks'),
			'time_created' => Yii::t('milestone', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	description', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	date_start,
					due_date', 
					'date', 'format' => 'yyyy-MM-dd', 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name', 
					'required', 'on' => 'create, update'),
			array(' attachments',
					'safe', 'on' => 'create, update'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'attachments' => array(self::MANY_MANY, 'File', '{{milestone_attachment}}(milestone_id,file_id)'),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'tasks' => array(self::HAS_MANY, 'Task', 'milestone_id'),
			'count_tasks' => array(self::STAT, 'Task', 'milestone_id'),
			'count_closed_tasks' => array(self::STAT, 'Task', 'milestone_id', 
				'condition' => 'phase = :phase_closed', 
				'params' => array(
					':phase_closed' => Task::PHASE_CLOSED,
				),
			),
		);
	}
	
	public function scopes()
	{
		return array(
			'member' => array(
				'with' => array(
					'project' => array(
						'scopes' => array(
							'member',
						),
					),
				),
			),
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
				// UploadFileBehavior MUST be defined before RelationBehavior
				'class' => 'UploadFileBehavior',
				'attributes' => array(
					'attachments',
				),
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'attachments',
					'created_by',
					'project',
					'tasks',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'milestone';
		$criteria->compare('milestone.name', $this->name, true);
		$criteria->with = array('count_tasks', 'count_closed_tasks');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'milestone.date_start, milestone.name',
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
	
	public function getTasks($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->compare('milestone_id', $this->id);
		$criteria->order = 'due_date, date_sheduled, priority DESC';
		return Task::model()->findAll($criteria);
	}
}

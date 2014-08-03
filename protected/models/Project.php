<?php

class Project extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{project}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'assignments' => Yii::t('project', 'Assignments'),
			'created_by_id' => Yii::t('project', 'Created by'),
			'created_by' => Yii::t('project', 'Created by'),
			'date_created' => Yii::t('project', 'Date Created'),
			'milestones' => Yii::t('project', 'Milestones'),
			'count_milestones' => Yii::t('project', 'Milestones'),
			'name' => Yii::t('project', 'Name'),
			'scope' => Yii::t('project', 'Scope'),
			'tasks' => Yii::t('project', 'Tasks'),
			'count_tasks' => Yii::t('project', 'Tasks'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	name', 
					'length', 'max' => 200),
			array('	name', 
					'required'),
			array('	scope', 
					'length', 'max' => 16000),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'assignments' => array(self::HAS_MANY, 'Assignment', 'project_id'),
			'user_assignment' => array(self::HAS_ONE, 'Assignment', 'project_id', 
				'on' => 'user_assignment.user_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				)),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'milestones' => array(self::HAS_MANY, 'Milestone', 'project_id'),
			'count_milestones' => array(self::STAT, 'Milestone', 'project_id'),
			'tasks' => array(self::HAS_MANY, 'Task', 'project_id'),
			'count_tasks' => array(self::STAT, 'Task', 'project_id'),
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
					'assignments' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'created_by',
					'milestones' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'tasks' => array(
						'cascadeDelete' => true,
					),
				),
			),
		);
	}
	
	public function scopes()
	{
		return array(
			'member' => array(
				'with' => array(
					'user_assignment' => array(
						'select' => false,
						'joinType' => 'INNER JOIN',
					)
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'project';
		$criteria->compare('project.name', $this->name, true);
		$criteria->with = array('count_milestones', 'count_tasks');
		
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

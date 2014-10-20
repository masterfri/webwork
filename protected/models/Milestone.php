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
			'created_by_id' => Yii::t('milestone', 'Created by'),
			'created_by' => Yii::t('milestone', 'Created by'),
			'description' => Yii::t('milestone', 'Description'),
			'due_date' => Yii::t('milestone', 'Due Date'),
			'name' => Yii::t('milestone', 'Name'),
			'project_id' => Yii::t('milestone', 'Project'),
			'project' => Yii::t('milestone', 'Project'),
			'tasks' => Yii::t('milestone', 'Tasks'),
			'time_created' => Yii::t('milestone', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	description', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	due_date', 
					'date', 'format' => 'yyyy-MM-dd', 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name', 
					'required', 'on' => 'create, update'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'tasks' => array(self::HAS_MANY, 'Task', 'milestone_id'),
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
					'project',
					'tasks',
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

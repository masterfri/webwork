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
			'project' => Yii::t('tag', 'Project'),
			'project_id' => Yii::t('tag', 'Project'),
			'time_created' => Yii::t('tag', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	color', 
					'length', 'max' => 20, 'on' => 'create, update, createShared, updateShared'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update, createShared, updateShared'),
			array('	name', 
					'required', 'on' => 'create, update, createShared, updateShared'),
			array(' project_id',
					'safe', 'on' => 'create, update'),
			array(' project_id',
					'required', 'on' => 'createShared, updateShared'),
			array(' project_id',
					'checkProject', 'on' => 'createShared, updateShared'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
		);
	}
	
	public function scopes()
	{
		return array(
			'member' => array(
				'with' => array(
					'project' => array(
						'select' => false,
						'condition' => 'tag.project_id = 0 OR NOT ISNULL(user_assignment.project_id)',
						'with' => array(
							'user_assignment' => array(
								'select' => false,
							),
						),
					),
				),
			),
			'active' => array(
				'with' => array(
					'project' => array(
						'condition' => 'tag.project_id = 0 OR project.archived = 0',
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
				'class' => 'RelationBehavior',
				'attributes' => array(
					'created_by',
					'project',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'tag';
		$criteria->compare('tag.name', $this->name, true);
		$criteria->with = array('project');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'tag.name DESC',
				'attributes' => array(
					'project' => array(
						'asc' => 'project.name ASC',
						'desc' => 'project.name DESC',
					),
					'*',
				)
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
	
	public function checkProject($attribute, $params=array())
	{
		if ($this->project_id > 0) {
			$assignment = Assignment::model()->find('project_id = :project AND user_id = :user', array(
				':project' => $this->project_id,
				':user' => Yii::app()->user->id,
			));
			if (null === $assignment) {
				$this->addError('project_id', Yii::t('tag', 'This project can not be selected'));
			}
		}
	}
}

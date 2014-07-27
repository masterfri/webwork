<?php

class Task extends CActiveRecord  
{
	const RISK_HIGH = 1;
	const RISK_MEDIUM = 2;
	const RISK_LOW = 3;
	const RISK_NONE = 4;
	
	const PRIORITY_CRITICAL = 1;
	const PRIORITY_URGENT = 2;
	const PRIORITY_HIGH = 3;
	const PRIORITY_MEDIUM = 4;
	const PRIORITY_LOW = 5;
	const PRIORITY_ON_HOLD = 6;
	
	const PHASE_CREATED = 1;
	const PHASE_SCHEDULED = 2;
	const PHASE_IN_PROGRESS = 3;
	const PHASE_PENDING = 4;
	const PHASE_NEW_ITERATION = 5;
	const PHASE_CLOSED = 6;
	const PHASE_ON_HOLD = 7;

	protected static $regression_risks;
	protected static $priorities;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{task}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'assigned_id' => Yii::t('task', 'Assigned'),
			'assigned' => Yii::t('task', 'Assigned'),
			'assignments' => Yii::t('task', 'Assignments'),
			'comments' => Yii::t('task', 'Comments'),
			'complexity' => Yii::t('task', 'Complexity'),
			'created_by_id' => Yii::t('task', 'Created by'),
			'created_by' => Yii::t('task', 'Created by'),
			'date_sheduled' => Yii::t('task', 'Date Sheduled'),
			'description' => Yii::t('task', 'Description'),
			'due_date' => Yii::t('task', 'Due Date'),
			'estimate' => Yii::t('task', 'Estimate'),
			'milestone_id' => Yii::t('task', 'Milestone'),
			'milestone' => Yii::t('task', 'Milestone'),
			'name' => Yii::t('task', 'Name'),
			'phase' => Yii::t('task', 'Phase'),
			'priority' => Yii::t('task', 'Priority'),
			'project_id' => Yii::t('task', 'Project'),
			'project' => Yii::t('task', 'Project'),
			'regression_risk' => Yii::t('task', 'Risk of Regression'),
			'tags' => Yii::t('task', 'Tags'),
			'time_created' => Yii::t('task', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	assigned_id,
					milestone_id,
					tags', 
					'safe'),
			array('	complexity,
					estimate', 
					'numerical'),
			array('	date_sheduled,
					due_date', 
					'date', 'format' => 'yyyy-MM-dd'),
			array('	description', 
					'length', 'max' => 16000),
			array('	name', 
					'length', 'max' => 200),
			array('	name', 
					'required'),
			array('	priority', 
					'in', 'range' => array(1, 2, 3, 4, 5, 6)),
			array('	regression_risk', 
					'in', 'range' => array(1, 2, 3, 4)),
			array('	assigned_id,
					name,
					phase,
					priority,
					regression_risk', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'assigned' => array(self::BELONGS_TO, 'User', 'assigned_id'),
			'assignments' => array(self::HAS_MANY, 'Assignment', 'task_id'),
			'comments' => array(self::HAS_MANY, 'Comment', 'task_id', 'order' => 'comments.time_created'),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'milestone' => array(self::BELONGS_TO, 'Milestone', 'milestone_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'tags' => array(self::MANY_MANY, 'Tag', '{{task_tag_tags}}(task_id,tag_id)'),
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
					'assigned',
					'assignments' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'comments' => array(
						'cascadeDelete' => true,
					),
					'created_by',
					'milestone',
					'project',
					'tags',
				),
			),
		);
	}
	
	public function scopes()
	{
		return array(
			'my' => array(
				'condition' => 'task.created_by_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				),
			),
			'assigned' => array(
				'condition' => 'task.assigned_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				),
			),
			'member' => array(
				'with' => array(
					'project' => array(
						'scopes' => array(
							'member',
						),
					),
				),
			),
			'new' => array(
				'condition' => 'task.phase IN(:phase_created, :phase_new_iteration)',
				'params' => array(
					':phase_created' => self::PHASE_CREATED,
					':phase_new_iteration' => self::PHASE_NEW_ITERATION,
				),
			),
			'pending' => array(
				'condition' => 'task.phase = :phase_pending',
				'params' => array(
					':phase_pending' => self::PHASE_PENDING,
				),
			),
			'closed' => array(
				'condition' => 'task.phase = :phase_closed',
				'params' => array(
					':phase_closed' => self::PHASE_CLOSED,
				),
			),
			'on_hold' => array(
				'condition' => 'task.phase = :phase_on_hold',
				'params' => array(
					':phase_on_hold' => self::PHASE_ON_HOLD,
				),
			),
			'outstanding' => array(
				'condition' => 'task.due_date != "0000-00-00" AND task.due_date >= DATE(NOW())',
			),
			'expired' => array(
				'condition' => 'task.due_date != "0000-00-00" AND task.due_date < DATE(NOW())',
			),
		);
	}
	
	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->getIsNewRecord()) {
				$this->phase = self::PHASE_CREATED;
			}
			return true;
		}
		return false;
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'task';
		$criteria->compare('task.assigned_id', $this->assigned_id);
		$criteria->compare('task.name', $this->name, true);
		$criteria->compare('task.phase', $this->phase);
		$criteria->compare('task.priority', $this->priority);
		$criteria->compare('task.regression_risk', $this->regression_risk);
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
	
	public static function getListRegressionRisks()
	{
		if (null === self::$regression_risks) {
			self::$regression_risks = array(
				self::RISK_HIGH => Yii::t('task', 'High'),
				self::RISK_MEDIUM => Yii::t('task', 'Medium'),
				self::RISK_LOW => Yii::t('task', 'Low'),
				self::RISK_NONE => Yii::t('task', 'None'),
			);
		}
		return self::$regression_risks;
	}
	
	public function getRegressionRisk()
	{
		return array_key_exists($this->regression_risk, self::getListRegressionRisks()) ? self::$regression_risks[$this->regression_risk] : '';
	}
	
	public static function getListPriorities()
	{
		if (null === self::$priorities) {
			self::$priorities = array(
				self::PRIORITY_CRITICAL => Yii::t('task', 'Critical'),
				self::PRIORITY_URGENT => Yii::t('task', 'Urgent'),
				self::PRIORITY_HIGH => Yii::t('task', 'High'),
				self::PRIORITY_MEDIUM => Yii::t('task', 'Medium'),
				self::PRIORITY_LOW => Yii::t('task', 'Low'),
				self::PRIORITY_ON_HOLD => Yii::t('task', 'On hold'),
			);
		}
		return self::$priorities;
	}
	
	public function getPriority()
	{
		return array_key_exists($this->priority, self::getListPriorities()) ? self::$priorities[$this->priority] : '';
	}
}

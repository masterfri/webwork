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
	
	const ACTION_COMMENT = 'comment';
	const ACTION_START_WORK = 'start-work';
	const ACTION_COMPLETE_WORK = 'complete-work';
	const ACTION_RETURN = 'return';
	const ACTION_CLOSE = 'close';
	const ACTION_PUT_ON_HOLD = 'put-on-hold';
	const ACTION_REOPEN = 'reopen';
	const ACTION_RESUME = 'resume';

	protected static $regression_risks;
	protected static $priorities;
	
	protected static $action_graph = array(
		self::ACTION_COMMENT => array(
			self::PHASE_CREATED,
			self::PHASE_SCHEDULED,
			self::PHASE_IN_PROGRESS,
			self::PHASE_PENDING,
			self::PHASE_NEW_ITERATION,
			self::PHASE_ON_HOLD,
		),
		self::ACTION_START_WORK => array(
			self::PHASE_CREATED,
			self::PHASE_SCHEDULED,
			self::PHASE_PENDING,
			self::PHASE_NEW_ITERATION,
		),
		self::ACTION_COMPLETE_WORK => array(
			self::PHASE_IN_PROGRESS,
		),
		self::ACTION_RETURN => array(
			self::PHASE_PENDING,
		),
		self::ACTION_CLOSE => array(
			self::PHASE_CREATED,
			self::PHASE_SCHEDULED,
			self::PHASE_IN_PROGRESS,
			self::PHASE_PENDING,
			self::PHASE_NEW_ITERATION,
			self::PHASE_ON_HOLD,
		),
		self::ACTION_PUT_ON_HOLD => array(
			self::PHASE_CREATED,
			self::PHASE_SCHEDULED,
			self::PHASE_IN_PROGRESS,
			self::PHASE_PENDING,
			self::PHASE_NEW_ITERATION,
		),
		self::ACTION_REOPEN => array(
			self::PHASE_CLOSED,
		),
		self::ACTION_RESUME => array(
			self::PHASE_ON_HOLD,
		),
	);
	
	protected static $phase_graph = array(
		self::ACTION_START_WORK => self::PHASE_IN_PROGRESS,
		self::ACTION_COMPLETE_WORK => self::PHASE_PENDING,
		self::ACTION_RETURN => self::PHASE_NEW_ITERATION,
		self::ACTION_CLOSE => self::PHASE_CLOSED,
		self::ACTION_PUT_ON_HOLD => self::PHASE_ON_HOLD,
		self::ACTION_REOPEN => self::PHASE_NEW_ITERATION,
		self::ACTION_RESUME => self::PHASE_NEW_ITERATION,
	);
	
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
			'subscriptions' => Yii::t('task', 'Subscriptions'),
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
			'user_assignment' => array(self::HAS_ONE, 'Assignment', 'task_id',
				'on' => 'user_assignment.user_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				)),
			'comments' => array(self::HAS_MANY, 'Comment', 'task_id', 'order' => 'comments.time_created'),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'milestone' => array(self::BELONGS_TO, 'Milestone', 'milestone_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'subscriptions' => array(self::HAS_MANY, 'Subscription', 'task_id'),
			'user_subscription' => array(self::HAS_ONE, 'Subscription', 'task_id',
				'on' => 'user_subscription.user_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				)),
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
					'subscriptions' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
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
			'scheduled' => array(
				'condition' => 'task.phase = :phase_scheduled',
				'params' => array(
					':phase_scheduled' => self::PHASE_SCHEDULED,
				),
			),
			'outstanding' => array(
				'condition' => 'task.due_date != "0000-00-00" AND task.due_date >= DATE(NOW())',
			),
			'expired' => array(
				'condition' => 'task.due_date != "0000-00-00" AND task.due_date < DATE(NOW())',
			),
			'updated' => array(
				'condition' => 'user_subscription.last_view_time < task.time_updated',
				'with' => array(
					'user_subscription' => array(
						'select' => false,
						'joinType' => 'INNER JOIN',
					)
				),
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
	
	public function getPhase()
	{
		switch ($this->phase) {
			case self::PHASE_CREATED:
				return Yii::t('task', 'New');
			case self::PHASE_SCHEDULED:
				return Yii::t('task', 'Scheduled');
			case self::PHASE_IN_PROGRESS:
				return Yii::t('task', 'In progress');
			case self::PHASE_PENDING:
				return Yii::t('task', 'Pending');
			case self::PHASE_NEW_ITERATION:
				return Yii::t('task', 'New iteration');
			case self::PHASE_CLOSED:
				return Yii::t('task', 'Closed');
			case self::PHASE_ON_HOLD:
				return Yii::t('task', 'On-hold');
		}
		return '';
	}
	
	public function getIsActionAvailable($action)
	{
		return 	array_key_exists($action, self::$action_graph) &&
				in_array($this->phase, self::$action_graph[$action]);
	}
	
	public function subscribe($user_id)
	{
		$model = Subscription::model()->find('task_id = ? AND user_id = ?', array($this->id, $user_id));
		if ($model === null) {
			$model = new Subscription();
			$model->task_id = $this->id;
			$model->user_id = $user_id;
			$model->last_view_time = '0000-00-00 00:00:00';
			$model->save();
		}
		return $model;
	}
	
	public function unsubscribe($user_id)
	{
		Subscription::model()->deleteAll('task_id = ? AND user_id = ?', array($this->id, $user_id));
	}
	
	public function unsubscribeAll()
	{
		Subscription::model()->deleteAll('task_id = ?', array($this->id));
	}
	
	public function doAction($action)
	{
		if (array_key_exists($action, self::$phase_graph)) {
			$this->phase = self::$phase_graph[$action];
		}
		$this->time_updated = date('Y-m-d H:i:s');
		$this->update(array('time_updated', 'phase'));
	}
}

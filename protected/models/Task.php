<?php

class Task extends CActiveRecord  
{
	const RISK_HIGH = 1;
	const RISK_MEDIUM = 2;
	const RISK_LOW = 3;
	const RISK_NONE = 4;
	
	const PRIORITY_CRITICAL = 6;
	const PRIORITY_URGENT = 5;
	const PRIORITY_HIGH = 4;
	const PRIORITY_MEDIUM = 3;
	const PRIORITY_LOW = 2;
	const PRIORITY_LOWEST = 1;
	
	const PHASE_CREATED = 1;
	const PHASE_SCHEDULED = 2;
	const PHASE_IN_PROGRESS = 3;
	const PHASE_PENDING = 4;
	const PHASE_NEW_ITERATION = 5;
	const PHASE_CLOSED = 6;
	const PHASE_ON_HOLD = 7;
	
	const ACTION_COMMENT = 'comment';
	const ACTION_START_WORK = 'start';
	const ACTION_COMPLETE_WORK = 'complete';
	const ACTION_RETURN = 'return';
	const ACTION_CLOSE = 'close';
	const ACTION_PUT_ON_HOLD = 'hold';
	const ACTION_REOPEN = 'reopen';
	const ACTION_RESUME = 'resume';
	
	const COMPLEXITY_RATE = 1;
	const ESTIMATE_RESERVE_RATE = 0.2;

	protected static $regression_risks;
	protected static $priorities;
	protected static $phases;
	
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
			'attachments' => Yii::t('task', 'Attachments'),
			'comments' => Yii::t('task', 'Comments'),
			'complexity' => Yii::t('task', 'Complexity'),
			'created_by_id' => Yii::t('task', 'Created by'),
			'created_by' => Yii::t('task', 'Created by'),
			'date_sheduled' => Yii::t('task', 'Date Scheduled'),
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
			array('	milestone_id', 
					'safe', 'on' => 'create, update'),
			array('	tags', 
					'safe', 'on' => 'create, update, change-tags'),
			array('	assigned_id', 
					'safe', 'on' => 'create, update, change-assignment'),
			array('	complexity,
					estimate', 
					'numerical', 'on' => 'create, update, estimate'),
			array('	date_sheduled,
					due_date', 
					'date', 'format' => 'yyyy-MM-dd', 'on' => 'create, update'),
			array('	description', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name', 
					'required', 'on' => 'create, update'),
			array('	complexity', 
					'required', 'on' => 'estimate'),
			array('	priority', 
					'in', 'range' => array_keys(self::getListPriorities()), 'on' => 'create, update, change-priority'),
			array('	regression_risk', 
					'in', 'range' => array_keys(self::getListRegressionRisks()), 'on' => 'create, update, estimate'),
			array(' attachments',
					'safe', 'on' => 'create, update'),
			array('	assigned_id,
					milestone_id,
					name,
					phase,
					priority,
					project_id,
					regression_risk,
					tags', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'assigned' => array(self::BELONGS_TO, 'User', 'assigned_id'),
			'assignments' => array(self::HAS_MANY, 'Assignment', 'task_id'),
			'attachments' => array(self::MANY_MANY, 'File', '{{task_attachment}}(task_id,file_id)'),
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
				// UploadFileBehavior MUST be defined before RelationBehavior
				'class' => 'UploadFileBehavior',
				'attributes' => array(
					'attachments',
				),
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'assigned',
					'assignments' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'attachments',
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
				'condition' => 'task.created_by_id = :current_user_id AND task.phase != :phase_closed',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
					':phase_closed' => self::PHASE_CLOSED,
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
			'active' => array(
				'with' => array(
					'project' => array(
						'joinType' => 'INNER JOIN',
						'scopes' => array(
							'active',
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
				'condition' => 'task.phase IN(:phase_scheduled, :phase_in_progress)',
				'params' => array(
					':phase_scheduled' => self::PHASE_SCHEDULED,
					':phase_in_progress' => self::PHASE_IN_PROGRESS,
				),
			),
			'outstanding' => array(
				'condition' => 'task.due_date != "0000-00-00" AND task.due_date >= DATE(NOW())',
			),
			'expired' => array(
				'condition' => 'task.due_date != "0000-00-00" AND task.due_date < DATE(NOW()) AND task.phase NOT IN(:phase_closed, :phase_on_hold)',
				'params' => array(
					':phase_closed' => self::PHASE_CLOSED,
					':phase_on_hold' => self::PHASE_ON_HOLD,
				),
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
			if ($this->canBeScheduled()) {
				$this->phase = self::PHASE_SCHEDULED;
			}
			return true;
		}
		return false;
	}
	
	protected function afterSave()
	{
		if ($this->getIsNewRecord()) {
			$this->subscribeTeam();
		}
		parent::afterSave();
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'task';
		$criteria->compare('task.assigned_id', $this->assigned_id);
		$criteria->compare('task.milestone_id', $this->milestone_id);
		$criteria->compare('task.name', $this->name, true);
		$criteria->compare('task.phase', $this->phase);
		$criteria->compare('task.priority', $this->priority);
		$criteria->compare('task.project_id', $this->project_id);
		$criteria->compare('task.regression_risk', $this->regression_risk);
		$criteria->with = array('assigned');
		if ($this->hasRelated('tags') && !empty($this->tags)) {
			$tmp = new CDbCriteria();
			$tmp->addInCondition('tags.id', $this->tags);
			$criteria->with['tags'] = array(
				'select' => false,
				'joinType' => 'INNER JOIN',
				'condition' => $tmp->condition,
				'params' => $tmp->params,
				'together' => true,
			);
			$criteria->group = 'task.id';
		}
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'task.time_created DESC',
				'attributes' => array(
					'assigned' => array(
						'asc' => 'assigned.real_name, assigned.username',
						'desc' => 'assigned.real_name DESC, assigned.username DESC',
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
				self::PRIORITY_LOWEST => Yii::t('task', 'Lowest'),
			);
		}
		return self::$priorities;
	}
	
	public function getPriority()
	{
		return array_key_exists($this->priority, self::getListPriorities()) ? self::$priorities[$this->priority] : '';
	}
	
	public static function getListPhases()
	{
		if (null === self::$phases) {
			self::$phases = array(
				self::PHASE_CREATED => Yii::t('task', 'New'),
				self::PHASE_SCHEDULED => Yii::t('task', 'Scheduled'),
				self::PHASE_IN_PROGRESS => Yii::t('task', 'In progress'),
				self::PHASE_PENDING => Yii::t('task', 'Pending'),
				self::PHASE_NEW_ITERATION => Yii::t('task', 'New iteration'),
				self::PHASE_CLOSED => Yii::t('task', 'Closed'),
				self::PHASE_ON_HOLD => Yii::t('task', 'On-hold'),
			);
		}
		return self::$phases;
	}
	
	public function getPhase()
	{
		return array_key_exists($this->phase, self::getListPhases()) ? self::$phases[$this->phase] : '';
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
			$model->last_view_time = MysqlDateHelper::EMPTY_DATETIME;
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
	
	protected function subscribeTeam()
	{
		foreach ($this->project->assignments as $assignment) {
			$this->subscribe($assignment->user_id);
		}
	}
	
	public function doAction($action)
	{
		if (array_key_exists($action, self::$phase_graph)) {
			if ($action == self::ACTION_REOPEN) {
				$this->date_sheduled = MysqlDateHelper::EMPTY_DATE;
			} elseif ($action == self::ACTION_START_WORK) {
				if (MysqlDateHelper::isEmpty($this->date_sheduled)) {
					$this->date_sheduled = MysqlDateHelper::currentDate();
				}
				$this->assigned_id = Yii::app()->user->id;
			}
			$this->phase = self::$phase_graph[$action];
		}
		$this->time_updated = MysqlDateHelper::currentDatetime();
		$this->update(array('time_updated', 'phase', 'date_sheduled', 'assigned_id'));
	}
	
	protected function canBeScheduled()
	{
		return  $this->phase == self::PHASE_CREATED &&
				$this->assigned !== null &&
				!MysqlDateHelper::isEmpty($this->date_sheduled);
	}
	
	public function getEstimate()
	{
		if ($this->estimate > 0) {
			return $this->estimate;
		} elseif ($this->complexity > 0) {
			$power = CHtml::value($this, 'assigned.rate.power');
			if ($power > 0) {
				return self::COMPLEXITY_RATE * $this->complexity / $power;
			}
		}
		return 0;
	}
	
	public function getEstimateReserve()
	{
		return self::ESTIMATE_RESERVE_RATE * $this->getEstimate();
	}
	
	public function getEstimateRange()
	{
		return array(
			$this->getEstimate(),
			$this->getEstimate() + $this->getEstimateReserve(),
		);
	}
}

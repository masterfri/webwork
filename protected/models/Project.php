<?php

class Project extends CActiveRecord  
{
	const BONUS_ABSOLUTE = 0;
	const BONUS_PERCENT = 1;
	
	protected static $bonus_types;
	
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
			'attachments' => Yii::t('project', 'Attachments'),
			'created_by_id' => Yii::t('project', 'Created by'),
			'created_by' => Yii::t('project', 'Created by'),
			'date_created' => Yii::t('project', 'Date Created'),
			'milestones' => Yii::t('project', 'Milestones'),
			'count_milestones' => Yii::t('project', 'Milestones'),
			'name' => Yii::t('project', 'Name'),
			'scope' => Yii::t('project', 'Scope'),
			'tasks' => Yii::t('project', 'Tasks'),
			'count_tasks' => Yii::t('project', 'Tasks'),
			'bonus' => Yii::t('project', 'Bonus'),
			'bonusValue' => Yii::t('project', 'Bonus'),
			'bonus_type' => Yii::t('project', 'Bonus Type'),
		);
	}
	
	public function rules()
	{
		$rules = array(
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name', 
					'required', 'on' => 'create, update'),
			array('	scope', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array(' attachments',
					'safe', 'on' => 'create, update'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
		if (Yii::app()->user->checkAccess('set_project_bonus', array('project' => $this))) {
			$rules[] = array(' bonus',
							   'numerical', 'on' => 'create, update');
			$rules[] = array(' bonus_type',
							   'in', 'range' => array_keys(self::getListBonusTypes()) , 'on' => 'create, update');
		}
		return $rules;
	}
	
	public function relations()
	{
		return array(
			'applications' => array(self::HAS_MANY, 'Application', 'project_id'),
			'assignments' => array(self::HAS_MANY, 'Assignment', 'project_id'),
			'attachments' => array(self::MANY_MANY, 'File', '{{project_attachment}}(project_id,file_id)'),
			'user_assignment' => array(self::HAS_ONE, 'Assignment', 'project_id', 
				'on' => 'user_assignment.user_id = :current_user_id',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				)),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'milestones' => array(self::HAS_MANY, 'Milestone', 'project_id'),
			'notes' => array(self::HAS_MANY, 'Note', 'project_id'),
			'count_milestones' => array(self::STAT, 'Milestone', 'project_id'),
			'tasks' => array(self::HAS_MANY, 'Task', 'project_id'),
			'count_tasks' => array(self::STAT, 'Task', 'project_id'),
			'count_closed_tasks' => array(self::STAT, 'Task', 'project_id', 
				'condition' => 'phase = :phase_closed', 
				'params' => array(
					':phase_closed' => Task::PHASE_CLOSED,
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
				// UploadFileBehavior MUST be defined before RelationBehavior
				'class' => 'UploadFileBehavior',
				'attributes' => array(
					'attachments',
				),
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'applications' => array(
						'cascadeDelete' => true,
					),
					'assignments' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'attachments',
					'created_by',
					'milestones' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'notes' => array(
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
					),
				),
			),
			'active' => array(
				'condition' => 'project.archived = 0',
			),
			'archived' => array(
				'condition' => 'project.archived = 1',
			),
		);
	}
	
	protected function afterSave()
	{
		if ($this->getIsNewRecord()) {
			$this->assignOwner();
		}
		parent::afterSave();
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'project';
		$criteria->compare('project.name', $this->name, true);
		$criteria->with = array('count_milestones', 'count_tasks', 'count_closed_tasks');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'project.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public static function getList($params=array())
	{
		return CHtml::listData(self::getAll($params), 'id', 'name');
	}
	
	public static function getUserBundleList($active_only=true, $params=array())
	{
		return CHtml::listData(self::getUserBundle($active_only, $params), 'id', 'name');
	}
	
	public static function getAll($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'project';
		$criteria->order = 'name';
		return self::model()->findAll($criteria);
	}
	
	public static function getUserBundle($active_only=true, $params=array())
	{
		$criteria = new CDbCriteria($params);
		if ($active_only) {
			$criteria->scopes[] = 'active';
		}
		if (!Yii::app()->user->checkAccess('view_project', array('project' => '*'))) {
			$criteria->scopes[] = 'member';
		}
		return self::getAll($criteria);
	}
	
	public function getTeamList()
	{
		$data = CHtml::listData($this->assignments, 'user_id', 'user.displayName');
		asort($data);
		return $data;
	}
	
	public function getMilestoneList()
	{
		$data = CHtml::listData($this->milestones, 'id', 'name');
		asort($data);
		return $data;
	}
	
	public function isUserAssigned($user_id, $role=null)
	{
		if ($user_id) {
			if ($user_id == Yii::app()->user->id) {
				$assignment = $this->user_assignment;
			} else {
				$assignment = Assignment::model()->find('user_id = ? AND project_id = ?', array($user_id, $this->id));
			}
			if ($assignment) {
				if ($role === null || (is_array($role) ? in_array($assignment->role, $role) : $role == $assignment->role)) {
					return true;
				}
			}
		}
		return false;
	}
	
	protected function assignOwner()
	{
		$assignment = new Assignment('create');
		$assignment->project_id = $this->id;
		$assignment->user_id = Yii::app()->user->id;
		$assignment->role = Assignment::ROLE_OWNER;
		$assignment->save();
	}
	
	public function getTags()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'name';
		$criteria->addCondition('project_id IN(0, :project_id)');
		$criteria->params[':project_id'] = $this->id;
		return Tag::model()->findAll($criteria);
	}
	
	public function getTagList()
	{
		return CHtml::listData($this->getTags(), 'id', 'name');
	}
	
	public function getTasks($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->compare('project_id', $this->id);
		$criteria->order = 'due_date, date_sheduled, priority DESC';
		return Task::model()->findAll($criteria);
	}
	
	public function getMilestones($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->compare('project_id', $this->id);
		$criteria->order = 'due_date';
		return Milestone::model()->findAll($criteria);
	}
	
	public function archive()
	{
		$this->archived = 1;
		$this->update(array('archived'));
	}
	
	public function activate()
	{
		$this->archived = 0;
		$this->update(array('archived'));
	}
	
	public function getOwner()
	{
		foreach ($this->assignments as $assignment) {
			if ($assignment->role == Assignment::ROLE_OWNER) {
				return $assignment->user;
			}
		}
		return null;
	}

	public function getActivityLevel($days=10, $skipWeekend=true)
	{
		return TimeEntry::model()->getActivityLevel($this->id, null, $days, $skipWeekend);
	}
	
	public function getBalance($days=10, $skipWeekend=true)
	{
		$criteria = new CDbCriteria();
		$criteria->select = new CDbExpression('COUNT(id) AS cnt, DATE(time_created) AS date');
		$criteria->group = 'DATE(time_created)';
		$criteria->addCondition('time_created >= DATE_sub(NOW(), INTERVAL :days DAY)');
		$criteria->compare('project_id', $this->id);
		$criteria->params[':days'] = $days;
		$cb = $this->dbConnection->commandBuilder;
		$command = $cb->createFindCommand(Task::model()->tableName(), $criteria);
		$new_tasks = CHtml::listData($command->queryAll(), 'date', 'cnt');
		
		$criteria = new CDbCriteria();
		$criteria->alias = 'c';
		$criteria->select = new CDbExpression('COUNT(c.id) AS cnt, DATE(c.time_created) AS date');
		$criteria->group = 'DATE(c.time_created)';
		$criteria->join = 'INNER JOIN ' . Task::model()->tableName() . ' t ON t.id = c.task_id';
		$criteria->addCondition('c.time_created >= DATE_sub(NOW(), INTERVAL :days DAY)');
		$criteria->compare('t.project_id', $this->id);
		$criteria->compare('c.action', Task::ACTION_REOPEN);
		$criteria->params[':days'] = $days;
		$command = $cb->createFindCommand(Comment::model()->tableName(), $criteria);
		$reopened_tasks = CHtml::listData($command->queryAll(), 'date', 'cnt');
		
		$criteria = new CDbCriteria();
		$criteria->alias = 'c';
		$criteria->select = new CDbExpression('COUNT(c.id) AS cnt, DATE(c.time_created) AS date');
		$criteria->group = 'DATE(c.time_created)';
		$criteria->join = 'INNER JOIN ' . Task::model()->tableName() . ' t ON t.id = c.task_id';
		$criteria->addCondition('c.time_created >= DATE_sub(NOW(), INTERVAL :days DAY)');
		$criteria->compare('t.project_id', $this->id);
		$criteria->compare('c.action', Task::ACTION_CLOSE);
		$criteria->params[':days'] = $days;
		$command = $cb->createFindCommand(Comment::model()->tableName(), $criteria);
		$closed_tasks = CHtml::listData($command->queryAll(), 'date', 'cnt');
		
		$balance = array();
		$time = mktime(12, 0, 0) - 86400 * ($days - 1);
		for ($i = 0; $i < $days; $i++) {
			$date = date('Y-m-d', $time);
			$open = 0;
			$close = 0;
			if (isset($new_tasks[$date])) {
				$open += $new_tasks[$date];
			}
			if (isset($reopened_tasks[$date])) {
				$open += $reopened_tasks[$date];
			}
			if (isset($closed_tasks[$date])) {
				$close += $closed_tasks[$date];
			}
			if ($open > 0 || $close > 0 || !$skipWeekend || !WorkingHours::isWeekend($time)) {
				$balance[$date] = array($open, $close);
			}
			$time += 86400;
		}
		
		return $balance;
	}
	
	public static function getListBonusTypes()
	{
		if (null === self::$bonus_types) {
			self::$bonus_types = array(
				self::BONUS_ABSOLUTE => Yii::t('project', 'Amount'),
				self::BONUS_PERCENT => Yii::t('project', 'Percent'),
			);
		}
		return self::$bonus_types;
	}
	
	public function getBonusType()
	{
		return array_key_exists($this->bonus_type, self::getListBonusTypes()) ? self::$bonus_types[$this->bonus_type] : '';
	}
	
	public function getBonusValue()
	{
		if ($this->bonus == 0) {
			return Yii::t('core.crud', 'None');
		} elseif ($this->bonus_type == self::BONUS_ABSOLUTE) {
			return Yii::t('core.crud', '{amount} per hour', array('{amount}' => Yii::app()->format->formatMoney($this->bonus)));
		} elseif ($this->bonus_type == self::BONUS_PERCENT) {
			return sprintf('%s%%', (float) $this->bonus);
		} else {
			return '';
		}
	}
}

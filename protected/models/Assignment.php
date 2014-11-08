<?php

class Assignment extends CActiveRecord  
{
	const ROLE_OWNER = 1;
	const ROLE_SUPERVISOR = 2;
	const ROLE_MANAGER = 3;
	const ROLE_DOER = 4;
	const ROLE_TESTER = 5;
	
	protected static $roles;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{assignment}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'project_id' => Yii::t('assignment', 'Project'),
			'project' => Yii::t('assignment', 'Project'),
			'role' => Yii::t('assignment', 'Role'),
			'task_id' => Yii::t('assignment', 'Task'),
			'task' => Yii::t('assignment', 'Task'),
			'user_id' => Yii::t('assignment', 'User'),
			'user' => Yii::t('assignment', 'User'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	role', 
					'in', 'range' => array(self::ROLE_OWNER, self::ROLE_SUPERVISOR, self::ROLE_MANAGER, self::ROLE_DOER, self::ROLE_TESTER), 'on' => 'create, update'),
			array(' role',
					'validateRole', 'on' => 'create, update'),
			array(' user_id',
					'validateUser', 'on' => 'create, update'),
			array('	role,
					user_id', 
					'required', 'on' => 'create, update'),
		);
	}
	
	public function relations()
	{
		return array(
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
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
		$criteria->alias = 'assignment';
		$criteria->with = array('user');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'user.real_name, user.username',
				'attributes' => array(
					'user' => array(
						'asc' => 'user.real_name ASC, user.username ASC',
						'desc' => 'user.real_name DESC, user.username DESC',
					),
					'*',
				)
			),
		));
	}
	
	public function __toString()
	{
		return sprintf('%s (%s)', $this->user->getDisplayName(), $this->getRoleName());
	}
	
	public static function getListRoles()
	{
		if (null === self::$roles) {
			self::$roles = array(
				self::ROLE_OWNER => Yii::t('assignment', 'Owner'),
				self::ROLE_SUPERVISOR => Yii::t('assignment', 'Supervisor'),
				self::ROLE_MANAGER => Yii::t('assignment', 'Manager'),
				self::ROLE_DOER => Yii::t('assignment', 'Doer'),
				self::ROLE_TESTER => Yii::t('assignment', 'Tester'),
			);
		}
		return self::$roles;
	}
	
	public function getRoleName()
	{
		return array_key_exists($this->role, self::getListRoles()) ? self::$roles[$this->role] : '';
	}
	
	public function validateRole()
	{
		if (self::ROLE_OWNER == $this->role) {
			$criteria = new CDbCriteria();
			$criteria->compare('project_id', $this->project_id);
			$criteria->compare('task_id', $this->task_id);
			$criteria->compare('role', self::ROLE_OWNER);
			if (!$this->getIsNewRecord()) {
				$criteria->addCondition('id != :id');
				$criteria->params[':id'] = $this->id;
			}
			if ($this->count($criteria) > 0) {
				$this->addError('role', Yii::t('assignment', 'Project can not have more than one owner.'));
			}
		}
	}
	
	public function validateUser()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('project_id', $this->project_id);
		$criteria->compare('task_id', $this->task_id);
		$criteria->compare('user_id', $this->user_id);
		if (!$this->getIsNewRecord()) {
			$criteria->addCondition('id != :id');
			$criteria->params[':id'] = $this->id;
		}
		if ($this->count($criteria) > 0) {
			$this->addError('user_id', Yii::t('assignment', 'This user already assigned to this project.'));
		}
	}
}

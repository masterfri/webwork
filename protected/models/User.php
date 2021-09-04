<?php

class User extends CActiveRecord  
{
	const STATUS_DISABLED = 0;
	const STATUS_ENABLED = 1;
	const STATUS_LOCKED = 2;

	const LEGAL_INDIVIDUAL = 1;
	const LEGAL_LLC = 2;
	
	public $password_plain;
	public $password_confirm;
	
	protected static $locales;
	protected static $statuses;
	protected static $legalTypes;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return 'user';
	}
	
	public function attributeLabels()
	{
		return array(
			'email' => Yii::t('user', 'Email'),
			'username' => Yii::t('user', 'Username'),
			'real_name' => Yii::t('user', 'Real Name'),
			'role' => Yii::t('user', 'Role'),
			'rate' => Yii::t('user', 'Rate'),
			'rate_id' => Yii::t('user', 'Rate'),
			'roleName' => Yii::t('user', 'Role'),
			'status' => Yii::t('user', 'Status'),
			'statusName' => Yii::t('user', 'Status'),
			'password_plain' => Yii::t('user', 'Password'),
			'password_confirm' => Yii::t('user', 'Confirm Password'),
			'locale' => Yii::t('user', 'Locale'),
			'localeName' => Yii::t('user', 'Locale'),
			'document_locale' => Yii::t('user', 'Locale for documents'),
			'documentLocaleName' => Yii::t('user', 'Locale for documents'),
			'working_hours' => Yii::t('user', 'Working Hours'),
			'working_hours_id' => Yii::t('user', 'Working Hours'),
			'legal_signer_name' => Yii::t('user', 'Signer Name'),
			'legal_name' => Yii::t('user', 'Legal Name'),
        	'legal_type' => Yii::t('user', 'Legal Type'),
			'legalTypeName' => Yii::t('user', 'Legal Type'),
        	'legal_number' => Yii::t('user', 'Registration Number'),
        	'legal_address' => Yii::t('user', 'Legal Address'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	email', 
					'email', 'on' => 'create, update, updateProfile'),
			array('	email,
					username,
					locale,
					document_locale', 
					'required', 'on' => 'create, update, updateProfile'),
			array('	role,
					status', 
					'required', 'on' => 'create, update'),
			array(' email,
					username,
					real_name',
					'length', 'max' => 100, 'on' => 'create, update, updateProfile'),
			array('	legal_signer_name,
					legal_name', 
					'length', 'max' => 200, 'on' => 'create, update, updateProfile'),
			array('	legal_number', 
					'length', 'max' => 50, 'on' => 'create, update, updateProfile'),
			array('	legal_address', 
					'length', 'max' => 16000, 'on' => 'create, update, updateProfile'),
			array('	legal_type', 
					'in', 'range' => array_keys(self::getListLegalTypes()), 'on' => 'create, update, updateProfile'),
			array(' username', 
					'match', 'pattern' => '/^[a-z0-9 _.-]+$/i', 'message' => 'В логине можно использовать только латинские буквы, цыфры, пробелы, тире, точки и символ подчеркивания', 'on' => 'create, update, updateProfile'),
			array(' username', 
					'unique', 'on' => 'create'),
			array(' username', 
					'unique', 'criteria' => array('condition' => 'id != :id', 'params' => array(':id' => $this->id)), 'on' => 'update, updateProfile'),
			array(' email', 
					'unique', 'on' => 'create'),
			array(' email', 
					'unique', 'criteria' => array('condition' => 'id != :id', 'params' => array(':id' => $this->id)), 'on' => 'update, updateProfile'),
			array('	password_plain,
					password_confirm',
					'required', 'on' => 'create'),
			array(' password_plain', 
					'safe', 'on' => 'update, updateProfile'),
			array(' password_confirm', 
					'compare', 'compareAttribute' => 'password_plain', 'on' => 'create, update, updateProfile'),
			array(' rate_id,
					working_hours_id',
					'safe', 'on' => 'create, update'),
			array('	email,
					username,
					role', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'rate' => array(self::BELONGS_TO, 'Rate', 'rate_id'),
			'assignments' => array(self::HAS_MANY, 'Assignment', 'user_id'),
			'schedule' => array(self::HAS_MANY, 'TaskSchedule', 'user_id'),
			'working_hours' => array(self::BELONGS_TO, 'WorkingHours', 'working_hours_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'rate',
					'working_hours',
					'assignments' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'schedule' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
				),
			),
		);
	}
	
	protected function beforeSave()
	{
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				$this->date_created = time();
			}
			if (! empty($this->password_plain)) {
				$this->salt = $this->generateSalt();
				$this->password = $this->hashPassword($this->password_plain, $this->salt);
			}
			return true;
		} else {
			return false;
		}
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'user';
		if (!empty($this->username)) {
			$tmp = new CDbCriteria();
			$tmp->compare('user.username', $this->username, true);
			$tmp->compare('user.real_name', $this->username, true, 'OR');
			$criteria->mergeWith($tmp);
		}
		$criteria->compare('user.email', $this->email, true);
		$criteria->compare('user.role', $this->role, true);
		$criteria->with = array('rate');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'user.real_name, user.username',
				'attributes' => array(
					'rate' => array(
						'asc' => 'rate.name ASC',
						'desc' => 'rate.name DESC',
					),
					'*',
				)
			),
		));
	}
	
	public static function getList($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->order = 'real_name, username';
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'displayName');
	}
	
	public function validatePassword($password)
	{
		return $this->hashPassword($password, $this->salt) === $this->password;
	}

	public function hashPassword($password, $salt)
	{
		return md5($salt . $password);
	}
	
	public function generateRandomString($len=10)
	{
		return StrHelper::generateRandomString($len);
	}
	
	public function generateSalt()
	{
		return $this->generateRandomString(15);
	}
	
	public static function listRoles()
	{
		return Yii::app()->authManager->listRoles();
	}
	
	public static function getListLocales()
	{
		if (null === self::$locales) {
			self::$locales = array(
				'en' => 'English',
				'ru' => 'Русский',
				'uk' => 'Українська',
			);
		}
		return self::$locales;
	}
	
	public static function getListStatuses()
	{
		if (null === self::$statuses) {
			self::$statuses = array(
				self::STATUS_ENABLED => Yii::t('user', 'Active'),
				self::STATUS_DISABLED => Yii::t('user', 'Inactive'),
				self::STATUS_LOCKED => Yii::t('user', 'Locked'),
			);
		}
		return self::$statuses;
	}

	public static function getListLegalTypes()
	{
		if (null === self::$legalTypes) {
			self::$legalTypes = array(
				self::LEGAL_INDIVIDUAL => Yii::t('user', 'Individual Entrepreneur'),
				self::LEGAL_LLC => Yii::t('user', 'Limited Liability Company'),
			);
		}
		return self::$legalTypes;
	}
	
	public function getRoleName()
	{
		return Yii::app()->authManager->getRoleDescription($this->role);
	}
	
	public function getLocaleName()
	{
		return array_key_exists($this->locale, self::getListLocales()) ? self::$locales[$this->locale] : '';
	}

	public function getDocumentLocaleName()
	{
		return array_key_exists($this->document_locale, self::getListLocales()) ? self::$locales[$this->document_locale] : '';
	}
	
	public function getStatusName()
	{
		return array_key_exists($this->status, self::getListStatuses()) ? self::$statuses[$this->status] : '';
	}

	public function getLegalTypeName()
	{
		return array_key_exists($this->legal_type, self::getListLegalTypes()) ? self::$legalTypes[$this->legal_type] : '';
	}

	public function getLegalName()
	{
		return $this->legal_type == self::LEGAL_INDIVIDUAL 
			? $this->legal_name : "«{$this->legal_name}‎»";
	}
	
	public function __toString()
	{
		return $this->getDisplayName();
	}
	
	public function getDisplayName()
	{
		return empty($this->real_name) ? $this->username : $this->real_name;
	}
	
	public function getActivityLevel($days=10, $skipWeekend=true)
	{
		return TimeEntry::model()->getActivityLevel(null, $this->id, $days, $skipWeekend);
	}
}

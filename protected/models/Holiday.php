<?php

class Holiday extends CActiveRecord  
{
	protected static $months;
	protected static $dateranges;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{holiday}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('holiday', 'Created by'),
			'created_by' => Yii::t('holiday', 'Created by'),
			'day' => Yii::t('holiday', 'Day'),
			'date' => Yii::t('holiday', 'Start Date'),
			'dates' => Yii::t('holiday', 'Date'),
			'month' => Yii::t('holiday', 'Month'),
			'year' => Yii::t('holiday', 'Year'),
			'day2' => Yii::t('holiday', 'Day'),
			'date2' => Yii::t('holiday', 'End Date'),
			'month2' => Yii::t('holiday', 'Month'),
			'year2' => Yii::t('holiday', 'Year'),
			'name' => Yii::t('holiday', 'Name'),
			'for' => Yii::t('holiday', 'For'),
			'time_created' => Yii::t('holiday', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	day,
					month,
					year,
					day2,
					month2,
					year2', 
					'numerical', 'integerOnly' => true, 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	day,
					month,
					name', 
					'required', 'on' => 'create, update'),
			array('	for', 
					'safe', 'on' => 'create, update'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'for' => array(self::MANY_MANY, 'User', '{{user_holiday}}(holiday_id,user_id)'),
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
					'for',
				),
			),
		);
	}
	
	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->year <= 0) {
				$this->year = new CDbExpression('NULL');
			}
			if ($this->day2 <= 0) {
				$this->day2 = new CDbExpression('NULL');
			}
			if ($this->month2 <= 0) {
				$this->month2 = new CDbExpression('NULL');
			}
			if ($this->year2 <= 0) {
				$this->year2 = new CDbExpression('NULL');
			}
			return true;
		}
		return false;
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'holiday';
		$criteria->compare('holiday.name', $this->name, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'holiday.year, holiday.month, holiday.day, holiday.name',
			),
		));
	}
	
	public static function getDaysList()
	{
		return array_combine(range(1, 31), range(1, 31));
	}
	
	public static function getMonthList()
	{
		if (null === self::$months) {
			self::$months = array(
				1  => Yii::t('holiday', 'January'),
				2  => Yii::t('holiday', 'February'),
				3  => Yii::t('holiday', 'March'),
				4  => Yii::t('holiday', 'April'),
				5  => Yii::t('holiday', 'May'),
				6  => Yii::t('holiday', 'June'),
				7  => Yii::t('holiday', 'July'),
				8  => Yii::t('holiday', 'August'),
				9  => Yii::t('holiday', 'September'),
				10 => Yii::t('holiday', 'October'),
				11 => Yii::t('holiday', 'November'),
				12 => Yii::t('holiday', 'December'),
			);
		}
		return self::$months;
	}
	
	public function getMonthName()
	{
		return array_key_exists($this->month, self::getMonthList()) ? self::$months[$this->month] : '';
	}
	
	public function getMonth2Name()
	{
		return array_key_exists($this->month2, self::getMonthList()) ? self::$months[$this->month2] : '';
	}
	
	public function getDate()
	{
		$d1 = $this->day;
		$m1 = $this->getMonthName();
		$y1 = $this->year;
		$d2 = ($this->day2 > 0) ? $this->day2 : $d1;
		$m2 = ($this->month2 > 0) ? $this->getMonth2Name() : $m1;
		$y2 = ($this->year2 > 0) ? $this->year2 : $y1;
		if ($y1 > 0) {
			if ($y1 == $y2) {
				if ($m1 == $m2) {
					if ($d1 == $d2) {
						return sprintf('%d %s, %d', $d1, $m1, $y1);
					} else {
						return sprintf('%d - %d %s, %d', $d1, $d2, $m1, $y1);
					}
				} else {
					return sprintf('%d %s - %d %s, %d', $d1, $m1, $d2, $m2, $y1);
				}
			} else {
				return sprintf('%d %s, %d - %d %s, %d', $d1, $m1, $y1, $d2, $m2, $y2);
			}
		} else {
			if ($m1 == $m2) {
				if ($d1 == $d2) {
					return sprintf('%d %s', $d1, $m1);
				} else {
					return sprintf('%d - %d %s', $d1, $d2, $m1);
				}
			} else {
				return sprintf('%d %s - %d %s', $d1, $m1, $d2, $m2);
			}
		}
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public static function checkDate($d, $m, $y=null, $user_id=null)
	{
		if (null === self::$dateranges) {
			self::$dateranges = self::createDateranges();
		}
		if (null === $y) {
			$y = date('Y');
		}
		$timeyear = mktime(1,0,0, $m, $d, $y);
		$timenoyear = mktime(1,0,0, $m, $d);
		foreach (self::$dateranges as $range) {
			$time = $range['noyear'] ? $timenoyear : $timeyear;
			if ($time >= $range['start'] && $time <= $range['end']) {
				if ($range['users'] === array() || $user_id !== null && in_array($user_id, $range['users'])) {
					return true;
				}
			}
		}
		return false;
	}
	
	protected static function createDateranges()
	{
		$ranges = array();
		$y = date('Y');
		$builder = Yii::app()->db->commandBuilder;
		$criteria = new CDbCriteria();
		$criteria->select = 'user_id';
		$criteria->addCondition('holiday_id = :holiday');
		$command = $builder->createFindCommand('{{user_holiday}}', $criteria);
		foreach (self::model()->findAll() as $model) {
			$d1 = $model->day;
			$m1 = $model->month;
			$y1 = ($model->year > 0) ? $model->year : $y;
			$d2 = ($model->day2 > 0) ? $model->day2 : $d1;
			$m2 = ($model->month2 > 0) ? $model->month2 : $m1;
			$y2 = ($model->year2 > 0) ? $model->year2 : $y1;
			$ranges[$model->id] = array(
				'noyear' => ($model->year > 0) ? false : true,
				'start' => mktime(0,0,0, $m1, $d1, $y1),
				'end' => mktime(23,59,59, $m2, $d2, $y2),
				'users' => $command->queryColumn(array(':holiday' => $model->id)),
			);
		}
		return $ranges;
	}
}

<?php

class WorkingHours extends CActiveRecord  
{
	protected static $userhours = array();
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{working_hours}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('workingHours', 'Created by'),
			'created_by' => Yii::t('workingHours', 'Created by'),
			'name' => Yii::t('workingHours', 'Name'),
			'mon' => Yii::t('workingHours', 'Monday'),
			'tue' => Yii::t('workingHours', 'Thuesday'),
			'wed' => Yii::t('workingHours', 'Wednesday'),
			'thu' => Yii::t('workingHours', 'Thursday'),
			'fri' => Yii::t('workingHours', 'Friday'),
			'sat' => Yii::t('workingHours', 'Saturday'),
			'sun' => Yii::t('workingHours', 'Sunday'),
			'general' => Yii::t('workingHours', 'General'),
			'time_created' => Yii::t('workingHours', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	mon,
					tue,
					wed,
					thu,
					fri,
					sat,
					sun', 
					'numerical', 'integerOnly' => true, 'min' => 0, 'max' => 24, 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	general', 
					'boolean', 'on' => 'create, update'),
			array('	name,
					mon,
					tue,
					wed,
					thu,
					fri,
					sat,
					sun', 
					'required', 'on' => 'create, update'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
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
				),
			),
		);
	}
	
	public function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->general) {
				$this->updateAll(array(
					'general' => 0,
				), array(
					'condition' => 'general = 1 AND id != :id',
					'params' => array(':id' => (int)$this->id),
				));
			}
			return true;
		}
		return false;
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'workingHours';
		$criteria->compare('workingHours.name', $this->name, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'workingHours.name',
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
	
	public static function checkUserHours($user_id, $d, $m=null, $y=null)
	{
		if ($m === null) {
			$day = (int) $d;
		} else {
			$day = (int) date('N', mktime(0,0,0, $m, $d, $y === null ? date('Y') : $y));
		}
		if (!isset(self::$userhours[$user_id])) {
			$criteria = new CDbCriteria();
			$criteria->alias = 'wh';
			$criteria->join = 'INNER JOIN ' . User::model()->tableName() . ' `user` ON (`user`.`working_hours_id` = `wh`.id OR `wh`.`general` = 1) AND `user`.`id` = :user';
			$criteria->params[':user'] = $user_id;
			$criteria->order = 'IF(`user`.`working_hours_id` = `wh`.id, 0, 1)';
			$criteria->limit = 1;
			$wh = self::model()->find($criteria);
			if ($wh !== null) {
				self::$userhours[$user_id] = array(
					1 => $wh->mon,
					2 => $wh->tue,
					3 => $wh->wed,
					4 => $wh->thu,
					5 => $wh->fri,
					6 => $wh->sat,
					7 => $wh->sun,
				);
			} else {
				self::$userhours[$user_id] = array_fill(1, 7, 0);
			}
		}
		return self::$userhours[$user_id][$day];
	}
	
	public static function isWeekend($time)
	{
		return in_array(date('N', $time), array(6, 7));
	}
}

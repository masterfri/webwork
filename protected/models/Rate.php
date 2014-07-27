<?php

class Rate extends CActiveRecord  
{
	protected $_complete_matrix;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{rate}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('rate', 'Created by'),
			'created_by' => Yii::t('rate', 'Created by'),
			'description' => Yii::t('rate', 'Description'),
			'matrix' => Yii::t('rate', 'Matrix'),
			'name' => Yii::t('rate', 'Name'),
			'power' => Yii::t('rate', 'Power'),
			'time_created' => Yii::t('rate', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	description', 
					'length', 'max' => 16000),
			array('	name', 
					'length', 'max' => 200),
			array('	name,
					power', 
					'required'),
			array('	power', 
					'numerical'),
			array(' completeMatrix',
					'safe'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'matrix' => array(self::HAS_MANY, 'ActivityRate', 'rate_id'),
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
					'matrix' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
				),
			),
		);
	}
	
	protected function afterSave()
	{
		parent::afterSave();
		foreach ($this->getCompleteMatrix() as $rate) {
			$rate->rate_id = $this->id;
			$rate->save(false);
		}
	}
	
	protected function afterValidate()
	{
		parent::afterValidate();
		foreach ($this->getCompleteMatrix() as $rate) {
			$rate->validate();
		}
	}
	
	public function hasErrors($attribute=null)
	{
		foreach ($this->getCompleteMatrix() as $rate) {
			if ($rate->hasErrors()) {
				return true;
			}
		}
		return parent::hasErrors($attribute);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->compare('t.name', $this->name, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'name',
			),
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
		$criteria->order = 'name';
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'displayName');
	}
	
	public function getCompleteMatrix()
	{
		if (null === $this->_complete_matrix) {
			$this->_complete_matrix = array();
			$activities = Activity::model()->with(array(
				'rate' => array(
					'on' => 'rate.rate_id = :rate_id',
					'params' => array(':rate_id' => intval($this->id)),
				),
			))->findAll();
			foreach ($activities as $activity) {
				$rate = $activity->rate;
				if (null === $rate) {
					$rate = new ActivityRate();
				}
				$rate->activity = $activity;
				$this->_complete_matrix[$activity->id] = $rate;
			}
		}
		return $this->_complete_matrix;
	}
	
	public function setCompleteMatrix($values)
	{
		$matrix = $this->getCompleteMatrix();
		foreach ($values as $activity_id => $value) {
			if (isset($matrix[$activity_id])) {
				$matrix[$activity_id]->hour_rate = $value;
			}
		}
	}
}

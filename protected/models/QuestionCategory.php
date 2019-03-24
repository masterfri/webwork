<?php

class QuestionCategory extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{question_category}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('questionCategory', 'Created by'),
			'created_by' => Yii::t('questionCategory', 'Created by'),
			'name' => Yii::t('questionCategory', 'Name'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name', 
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
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'questionCategory';
		$criteria->compare('questionCategory.name', $this->name, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'questionCategory.name',
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
}

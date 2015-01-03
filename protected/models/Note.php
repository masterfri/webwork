<?php

class Note extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{note}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('note', 'Created by'),
			'created_by' => Yii::t('note', 'Created by'),
			'private' => Yii::t('note', 'Private'),
			'project' => Yii::t('note', 'Project'),
			'project_id' => Yii::t('note', 'Project'),
			'text' => Yii::t('note', 'Text'),
			'time_created' => Yii::t('note', 'Time Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	text', 
					'required', 'on' => 'create, update'),
			array('	private', 
					'boolean', 'on' => 'create, update'),
			array('	text', 
					'length', 'max' => 500, 'on' => 'create, update'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'project' => array(self::BELONGS_TO, 'Project', 'project_id'),
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
					'project',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'note';
		$criteria->with = array('created_by');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'note.time_created DESC',
			),
		));
	}
	
	public function __toString()
	{
		return $this->text;
	}
}

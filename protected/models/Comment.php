<?php

class Comment extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{comment}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'attachments' => Yii::t('comment', 'Attachments'),
			'content' => Yii::t('comment', 'Content'),
			'created_by_id' => Yii::t('comment', 'Created by'),
			'created_by' => Yii::t('comment', 'Created by'),
			'task_id' => Yii::t('comment', 'Task'),
			'task' => Yii::t('comment', 'Task'),
			'time_created' => Yii::t('comment', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	content', 
					'length', 'max' => 16000),
			array('	content', 
					'required'),
		);
	}
	
	public function relations()
	{
		return array(
			'attachments' => array(self::HAS_MANY, 'Attachment', 'comment_id'),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'task' => array(self::BELONGS_TO, 'Task', 'task_id'),
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
					'attachments' => array(
						'cascadeDelete' => true,
					),
					'created_by',
					'task',
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
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
		return "#".$this->primaryKey;
	}
	
	public static function getList()
	{
		$criteria = new CDbCriteria();
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'displayName');
	}
}

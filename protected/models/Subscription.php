<?php

class Subscription extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{subscription}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'last_view_time' => Yii::t('subscription', 'Last view time'),
			'task_id' => Yii::t('subscription', 'Task'),
			'task' => Yii::t('subscription', 'Task'),
			'user_id' => Yii::t('subscription', 'User'),
			'user' => Yii::t('subscription', 'User'),
		);
	}
	
	public function relations()
	{
		return array(
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
					'task',
					'user',
				),
			),
		);
	}
	
	public function markAsSeen()
	{
		$this->last_view_time = date('Y-m-d H:i:s');
		$this->save();
	}
}

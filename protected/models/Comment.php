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
					'required', 'on' => 'comment, return, reopen'),
			array(' attachments',
					'safe'),
		);
	}
	
	public function relations()
	{
		return array(
			'attachments' => array(self::MANY_MANY, 'File', '{{comment_attachment}}(comment_id,file_id)'),
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
				// UploadFileBehavior MUST be defined before RelationBehavior
				'class' => 'UploadFileBehavior',
				'attributes' => array(
					'attachments',
				),
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'attachments',
					'created_by',
					'task',
				),
			),
		);
	}
	
	public function getActionExplanation()
	{
		switch ($this->action) {
			case Task::ACTION_CLOSE: return '{author} closed this task {date}';
			case Task::ACTION_COMPLETE_WORK: return '{author} completed work on this task {date}';
			case Task::ACTION_PUT_ON_HOLD: return '{author} put this task on-hold {date}';
			case Task::ACTION_REOPEN: return '{author} opened this task {date}';
			case Task::ACTION_RESUME: return '{author} resumed this task {date}';
			case Task::ACTION_RETURN: return '{author} did not accept this task {date}';
			case Task::ACTION_START_WORK: return '{author} started work on this task {date}';
			default: return '{author} commented {date}';
		}
	}
}

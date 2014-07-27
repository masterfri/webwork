<?php

class Attachment extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{attachment}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'comment_id' => Yii::t('attachment', 'Comment'),
			'comment' => Yii::t('attachment', 'Comment'),
			'file_id' => Yii::t('attachment', 'File'),
			'file' => Yii::t('attachment', 'File'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	comment_id,
					file_id', 
					'safe'),
		);
	}
	
	public function relations()
	{
		return array(
			'comment' => array(self::BELONGS_TO, 'Comment', 'comment_id'),
			'file' => array(self::BELONGS_TO, 'File', 'file_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'comment',
					'file',
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

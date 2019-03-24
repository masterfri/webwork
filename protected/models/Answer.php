<?php

class Answer extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{answer}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'text' => Yii::t('answer', 'Answer'),
			'score' => Yii::t('answer', 'Score'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	text', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	score', 
					'numerical', 'min' => 0, 'max' => 100, 'integerOnly' => true, 'on' => 'create, update'),
			array('	text,
					score', 
					'required', 'on' => 'create, update'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'answer';
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
	
	public function __toString()
	{
		return $this->text;
	}
}

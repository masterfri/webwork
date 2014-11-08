<?php

class ActivityRate extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{activity_rate}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'activity_id' => Yii::t('activityRate', 'Activity'),
			'activity' => Yii::t('activityRate', 'Activity'),
			'hour_rate' => Yii::t('activityRate', 'Hour Rate'),
			'rate_id' => Yii::t('activityRate', 'Rate'),
			'rate' => Yii::t('activityRate', 'Rate'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	hour_rate', 
					'numerical'),
			array('	hour_rate', 
					'required'),
		);
	}
	
	public function relations()
	{
		return array(
			'activity' => array(self::BELONGS_TO, 'Activity', 'activity_id'),
			'rate' => array(self::BELONGS_TO, 'Rate', 'rate_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'activity',
					'rate',
				),
			),
		);
	}
}

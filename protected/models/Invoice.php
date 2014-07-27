<?php

class Invoice extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{invoice}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'comments' => Yii::t('invoice', 'Comments'),
			'created_by_id' => Yii::t('invoice', 'Created by'),
			'created_by' => Yii::t('invoice', 'Created by'),
			'items' => Yii::t('invoice', 'Items'),
			'payd' => Yii::t('invoice', 'Payd'),
			'project_id' => Yii::t('invoice', 'Project'),
			'project' => Yii::t('invoice', 'Project'),
			'time_created' => Yii::t('invoice', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	comments', 
					'length', 'max' => 16000),
			array('	payd', 
					'boolean'),
			array('	payd', 
					'required'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'items' => array(self::HAS_MANY, 'InvoiceItem', 'invoice_id'),
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
					'items' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'project',
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

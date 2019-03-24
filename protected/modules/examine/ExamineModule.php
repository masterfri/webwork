<?php

class ExamineModule extends CWebModule
{
	public function init()
	{
		Yii::import('examine.components.*');
		
		Yii::app()->messages->extensionPaths['examine'] = 'examine.messages';
		
		Yii::app()->errorHandler->errorAction = 'examine/default/error';
	}
	
	public function getVersion()
	{
		return '1.0';
	}
}
<?php

class CoreModule extends CWebModule
{
	public function init()
	{
		Yii::import('core.components.*');
		Yii::import('core.models.*');
		
		Yii::app()->messages->extensionPaths['core'] = 'core.messages';
	}
}

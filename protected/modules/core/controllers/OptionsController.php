<?php

class OptionsController extends AdminController 
{
	public function actionGeneralOptions()
	{
		$model = GeneralOptions::instance();
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Options have been updated'));
			$this->refresh();
		}
		$this->render('generalOptions', array(
			'model' => $model,
		));
	}
	
	public function filters()
	{
		return array(
			'accessControl',
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('generalOptions'),
				'roles' => array('update_general_options'),
			),
			array('deny'),
		);
	}
}

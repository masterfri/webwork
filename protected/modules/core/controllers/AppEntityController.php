<?php

class AppEntityController extends AdminController 
{
	public function actionIndex($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = $this->createSearchModel('AppEntity');
		$provider = $model->search(array(
			'condition' => 'application_id = :application_id',
			'params' => array(':application_id' => $application->id),
		));
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
			'application' => $application,
		));
	}
	
	public function actionCreate($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new AppEntity('create');
		$model->application = $application;
		$model->application_id = $application->id;
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'appEntity.created',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Application entity has been created'),
					),
				));
			} else {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('create', array(
			'model' => $model,
			'application' => $application,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'appEntity.updated',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Application entity has been updated'),
					),
				));
			} else {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'application' => $model->application->id));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$this->render('view', array(
			'model' => $model,
		));
	}

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete', 
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('create'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('design_application'),
			),
			array('deny'),
		);
	}
}

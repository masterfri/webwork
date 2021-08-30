<?php

class CompletionReportController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('CompletionReport');
		$provider = $model->search();
		if (!Yii::app()->user->checkAccess('view_completion_report', array('report' => '*'))) {
			$provider->criteria->scopes[] = 'my';
		}
		$criteria = clone($provider->criteria);
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionCreate()
	{
		$model = new CompletionReport('create');
		$model->performer_id = Yii::app()->user->id;
		$model->date = date('Y-m-d');
		$model->date_to = date('Y-m-d');
		$model->date_from = date('Y-m-01');
		$model->rememberRecentContract();
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Completion report has been created'));
			$this->redirect(array('update', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'CompletionReport');
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Completion report has been updated'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'CompletionReport');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'CompletionReport');
		if (!Yii::app()->user->checkAccess('view_completion_report', array('report' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$this->render('view', array(
			'model' => $model,
		));
	}
	
	public function actionPdf($id)
	{
		$model = $this->loadModel($id, 'CompletionReport');
		if (!Yii::app()->user->checkAccess('view_completion_report', array('report' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (null !== $model->contragent) {
			Yii::app()->language = $model->contragent->document_locale;
		}
		$this->renderPartial('pdf', array(
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
				'roles' => array('create_completion_report'),
			),
			array('allow',
				'actions' => array('view', 'index', 'pdf'),
				'roles' => array('view_completion_report'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_completion_report'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_completion_report'),
			),
			array('deny'),
		);
	}
}

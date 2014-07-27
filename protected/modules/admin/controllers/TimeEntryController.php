<?php

class TimeEntryController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('TimeEntry');
		$provider = $model->search();
		$sum = $model->getSum($provider->criteria);
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
			'sum' => $sum,
		));
	}
	
	public function actionCreate()
	{
		$model = new TimeEntry('create');
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'TimeEntry');
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'TimeEntry');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id, 'TimeEntry'),
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
				'roles' => array('create_time_entry'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_time_entry'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_time_entry'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_time_entry'),
			),
			array('deny'),
		);
	}
}

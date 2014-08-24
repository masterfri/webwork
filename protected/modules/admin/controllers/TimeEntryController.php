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
	
	public function actionDaily()
	{
		$model = $this->createSearchModel('TimeEntry');
		$provider = $model->search(array(
			'condition' => 't.user_id = :user_id AND DATE(t.date_created) = :today',
			'params' => array(
				':user_id' => Yii::app()->user->id,
				':today' => date('Y-m-d'),
			)
		));
		$sum = $model->getSum($provider->criteria);
		$this->render('daily', array(
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
	
	public function actionReport($task)
	{
		$task = $this->loadModel($task, 'Task');
		$model = new TimeEntry('create');
		$model->task_id = $task->id;
		$model->project_id = $task->project_id;
		$model->user_id = Yii::app()->user->id;
		if ($this->saveModel($model)) {
			$this->redirect(array('daily'));
		}
		$this->render('report', array(
			'model' => $model,
			'task' => $task,
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
				'actions' => array('report'),
				'roles' => array('report_time_entry'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_time_entry'),
			),
			array('allow',
				'actions' => array('daily'),
				'roles' => array('daily_time_report'),
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

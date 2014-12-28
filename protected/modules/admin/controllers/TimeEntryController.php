<?php

class TimeEntryController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('TimeEntry');
		$provider = $model->search();
		if (!Yii::app()->user->checkAccess('view_time_entry', array('entry' => '*'))) {
			$provider->criteria->scopes[] = 'my';
		}
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionDaily()
	{
		$model = $this->createSearchModel('TimeEntry');
		if (empty($model->date_created)) {
			$model->date_created = MysqlDateHelper::currentDate();
		}
		$provider = $model->search();
		if (!Yii::app()->user->checkAccess('view_time_entry', array('entry' => '*'))) {
			$provider->criteria->scopes[] = 'my';
		}
		$sum = $model->getSum($provider->criteria);
		$this->render('daily', array(
			'model' => $model,
			'provider' => $provider,
			'sum' => $sum,
		));
	}
	
	public function actionMonthly($export='')
	{
		$model = $this->createSearchModel('TimeEntry');
		if (empty($model->date_created)) {
			$model->date_created = date('Y-m');
		}
		$provider = $model->search();
		if (!Yii::app()->user->checkAccess('view_time_entry', array('entry' => '*'))) {
			$provider->criteria->scopes[] = 'my';
		}
		if ('csv' == $export) {
			$this->render('monthlyCsv', array(
				'model' => $model,
				'provider' => $provider,
			));
		} else {
			$sum = $model->getSum($provider->criteria);
			$this->render('monthly', array(
				'model' => $model,
				'provider' => $provider,
				'sum' => $sum,
			));
		}
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
	
	public function actionReport($task, $sec=0)
	{
		$task = $this->loadModel($task, 'Task');
		if (!Yii::app()->user->checkAccess('report_time_entry', array('task' => $task))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new TimeEntry('create');
		$model->task_id = $task->id;
		$model->project_id = $task->project_id;
		$model->user_id = Yii::app()->user->id;
		if ($sec > 0) {
			$model->amount = sprintf('%0.2f', $sec / 3600);
		}
		if ($this->saveModel($model)) {
			if($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'timeentry.created',
					'message' => array(
						'title' => Yii::t('admin.crud', 'Success'),
						'text' => Yii::t('admin.crud', 'Time has been reported'),
					),
				));
			} else {
				$this->redirect(array('daily'));
			}
		}
		if($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('report', array(
			'model' => $model,
			'task' => $task,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'TimeEntry');
		if (!Yii::app()->user->checkAccess('update_time_entry', array('entry' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
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
		if (!Yii::app()->user->checkAccess('delete_time_entry', array('entry' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'TimeEntry');
		if (!Yii::app()->user->checkAccess('view_time_entry', array('entry' => $model))) {
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
				'actions' => array('monthly'),
				'roles' => array('monthly_time_report'),
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

<?php

class UserController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('User');
		$provider = $model->search();
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionQuery($query)
	{
		$project = Yii::app()->request->getQuery('project');
		$model = $this->createSearchModel('User');
		$model->username = $query;
		$provider = $model->search();
		$criteria = $provider->criteria;
		if (!empty($project)) {
			$tmp = new CDbCriteria();
			$tmp->compare('assignments.project_id', $project);
			$criteria->with['assignments'] = array(
				'select' => false,
				'joinType' => 'INNER JOIN',
				'condition' => $tmp->condition,
				'params' => $tmp->params,
				'together' => true,
			);
			$criteria->group = 'user.id';
		}
		$criteria->limit = 15;
		$criteria->order = 'user.real_name, user.username';
		$results = array();
		foreach ($provider->getData() as $user) {
			$results[] = array(
				'id' => $user->id,
				'text' => $user->getDisplayName(),
			);
		}
		echo CJSON::encode($results);
	}
	
	public function actionCreate()
	{
		$model = new User('create');
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'User has been created'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'User');
		if ($this->saveModel($model)) {
			$user = Yii::app()->user;
			if ($model->id == $user->id) {
				$user->setLocale($model->locale);
			}
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'User has been updated'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'User');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'User');
		$monthnum = date('m');
		$month1 = date('F');
		$month2 = date('F', mktime(0,0,0, $monthnum + 1, 1));
		$month1start = 2 - date('N', mktime(0,0,0, $monthnum, 1));
		$month2start = 2 - date('N', mktime(0,0,0, $monthnum + 1, 1));
		$month1days = date('t', mktime(0,0,0, $monthnum, 1));
		$month2days = date('t', mktime(0,0,0, $monthnum + 1, 1));
		$currentTasks = Task::model()->search(array(
			'scopes' => array('scheduled'),
			'condition' => 'task.assigned_id = :assigned_id',
			'params' => array(':assigned_id' => $model->id),
		));
		$this->render('view', array(
			'model' => $model,
			'monthnum' => $monthnum,
			'month1' => $month1,
			'month2' => $month2,
			'month1i' => $month1start,
			'month2i' => $month2start,
			'month1days' => $month1days,
			'month2days' => $month2days,
			'currentTasks' => $currentTasks,
		));
	}
	
	public function actionProfile()
	{
		$monthnum = date('m');
		$month1 = date('F');
		$month2 = date('F', mktime(0,0,0, $monthnum + 1, 1));
		$month1start = 2 - date('N', mktime(0,0,0, $monthnum, 1));
		$month2start = 2 - date('N', mktime(0,0,0, $monthnum + 1, 1));
		$month1days = date('t', mktime(0,0,0, $monthnum, 1));
		$month2days = date('t', mktime(0,0,0, $monthnum + 1, 1));
		$this->render('profile', array(
			'model' => Yii::app()->user->getModel(),
			'monthnum' => $monthnum,
			'month1' => $month1,
			'month2' => $month2,
			'month1i' => $month1start,
			'month2i' => $month2start,
			'month1days' => $month1days,
			'month2days' => $month2days,
		));
	}
	
	public function actionUpdateProfile()
	{
		$model = Yii::app()->user->getModel();
		$model->setScenario('updateProfile');
		if ($this->saveModel($model)) {
			$user = Yii::app()->user->setLocale($model->locale);
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Your profile has been updated'));
			$this->redirect(array('profile'));
		}
		$this->render('updateProfile', array(
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
				'roles' => array('create_user'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_user'),
			),
			array('allow',
				'actions' => array('query'),
				'roles' => array('query_user'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_user'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_user'),
			),
			array('allow',
				'actions' => array('profile', 'updateProfile'),
				'users' => array('@'),
			),
			array('deny'),
		);
	}
}

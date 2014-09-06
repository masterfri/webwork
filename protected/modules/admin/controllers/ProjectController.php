<?php

class ProjectController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Project');
		$provider = $model->search();
		if (!Yii::app()->user->checkAccess('view_project', array('project' => '*'))) {
			$provider->criteria->scopes[] = 'member';
		}
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionCreate()
	{
		$model = new Project('create');
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Project');
		if (!Yii::app()->user->checkAccess('update_project', array('project' => $model))) {
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
		$model = $this->loadModel($id, 'Project');
		if (!Yii::app()->user->checkAccess('delete_project', array('project' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'Project');
		if (!Yii::app()->user->checkAccess('view_project', array('project' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$this->render('view', array(
			'model' => $this->loadModel($id, 'Project'),
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
				'roles' => array('create_project'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_project'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_project'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_project'),
			),
			array('deny'),
		);
	}
}

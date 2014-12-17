<?php

class ProjectController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Project');
		$provider = $model->search(array(
			'scopes' => array('active'),
		));
		if (!Yii::app()->user->checkAccess('view_project', array('project' => '*'))) {
			$provider->criteria->scopes[] = 'member';
		}
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionArchived()
	{
		$model = $this->createSearchModel('Project');
		$provider = $model->search(array(
			'scopes' => array('archived'),
		));
		if (!Yii::app()->user->checkAccess('view_project', array('project' => '*'))) {
			$provider->criteria->scopes[] = 'member';
		}
		$this->render('archived', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionQuery($query)
	{
		$model = $this->createSearchModel('Project');
		$model->name = $query;
		$criteria = new CDbCriteria();
		$criteria->limit = 15;
		$criteria->order = 'project.name';
		if (!Yii::app()->user->checkAccess('query_project', array('project' => '*'))) {
			$criteria->scopes = array('member');
		}
		$provider = $model->search($criteria);
		$results = array();
		foreach ($provider->getData() as $project) {
			$results[] = array(
				'id' => $project->id,
				'text' => $project->name,
			);
		}
		echo CJSON::encode($results);
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
	
	public function actionArchive($id)
	{
		$model = $this->loadModel($id, 'Project');
		if (!Yii::app()->user->checkAccess('archive_project', array('project' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->archive();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionActivate($id)
	{
		$model = $this->loadModel($id, 'Project');
		if (!Yii::app()->user->checkAccess('activate_project', array('project' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->activate();
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
			'model' => $model,
		));
	}
	
	public function actionPdf($id)
	{
		$model = $this->loadModel($id, 'Project');
		if (!Yii::app()->user->checkAccess('view_project', array('project' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (null !== ($owner = $model->getOwner())) {
			Yii::app()->language = $owner->locale;
		}
		$this->renderPartial('pdf', array(
			'model' => $model,
		));
	}

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete,archive,activate', 
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
				'actions' => array('view', 'index', 'pdf', 'archived'),
				'roles' => array('view_project'),
			),
			array('allow',
				'actions' => array('query'),
				'roles' => array('query_project'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_project'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_project'),
			),
			array('allow',
				'actions' => array('archive'),
				'roles' => array('archive_project'),
			),
			array('allow',
				'actions' => array('activate'),
				'roles' => array('activate_project'),
			),
			array('deny'),
		);
	}
}

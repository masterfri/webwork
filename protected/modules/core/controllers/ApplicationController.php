<?php

class ApplicationController extends AdminController 
{
	const WEB_DOC_ROOT = 'public_html';
	const WEB_LOGS = 'logs';
	const DB_NAME_PREFIX = 'db_';
	const DB_USER_NAME_PREFIX = 'usr_';
	
	public function actionIndex($project)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('view_application', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = $this->createSearchModel('Application');
		$provider = $model->search(array(
			'condition' => 'project_id = :project_id',
			'params' => array(':project_id' => $project->id),
		));
		$this->render('index', array(
			'model' => $model,
			'project' => $project,
			'provider' => $provider,
		));
	}
	
	public function actionCreate($project)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('create_application', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new Application('create');
		$model->project = $project;
		if ($this->saveModel($model)) {
			$this->redirect(array('configWeb', 'id' => $model->id, 'wizard' => 1));
		}
		$this->render('create', array(
			'model' => $model,
			'project' => $project,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Application');
		if (!Yii::app()->user->checkAccess('update_application', array('application' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionConfigWeb($id, $wizard=0)
	{
		$model = $this->loadModel($id, 'Application');
		if (!Yii::app()->user->checkAccess('update_application', array('application' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!($model->status & Application::STATUS_HAS_WEB)) {
			$model->document_root = self::WEB_DOC_ROOT;
			$model->log_directory = self::WEB_LOGS;
		}
		$model->setScenario('configwebserver');
		$response = null;
		if ($this->saveModel($model)) {
			$response = $model->setupVhost();
			if ($response->getIsSuccess()) {
				$model->setStatus(Application::STATUS_HAS_WEB);
				if ($wizard == 1) {
					$this->redirect(array('configGit', 'id' => $model->id, 'wizard' => 1));
				} else {
					$this->redirect(array('view', 'id' => $model->id));
				}
			} else {
				$model->unsetStatus(Application::STATUS_HAS_WEB);
			}
		}
		$this->render('configWeb', array(
			'model' => $model,
			'wizard' => $wizard,
			'response' => $response,
		));
	}
	
	public function actionConfigGit($id, $wizard=0)
	{
		$model = $this->loadModel($id, 'Application');
		if (!Yii::app()->user->checkAccess('update_application', array('application' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!($model->status & Application::STATUS_HAS_GIT)) {
			$model->git_branch = 'master';
			$model->create_repo = true;
		}
		$model->setScenario('configgit');
		$response = null;
		if ($this->saveModel($model)) {
			$response = $model->setupGit();
			if ($response->getIsSuccess()) {
				$model->setStatus(Application::STATUS_HAS_GIT);
				if ($wizard == 1) {
					$this->redirect(array('configDb', 'id' => $model->id, 'wizard' => 1));
				} else {
					$this->redirect(array('view', 'id' => $model->id));
				}
			} else {
				$model->unsetStatus(Application::STATUS_HAS_GIT);
			}
		}
		$this->render('configGit', array(
			'model' => $model,
			'wizard' => $wizard,
			'response' => $response,
		));
	}
	
	public function actionConfigDb($id, $wizard=0)
	{
		$model = $this->loadModel($id, 'Application');
		if (!Yii::app()->user->checkAccess('update_application', array('application' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!($model->status & Application::STATUS_HAS_DB)) {
			$model->db_name = self::DB_NAME_PREFIX . preg_replace('/[^a-z0-9_]/i', '_', $model->name);
			$model->db_user = self::DB_USER_NAME_PREFIX . preg_replace('/[^a-z0-9_]/i', '_', $model->name);
			$model->db_password = $this->generatePassword();
		}
		$model->setScenario('configdb');
		$response = null;
		if ($this->saveModel($model)) {
			$response = $model->setupDb();
			if ($response->getIsSuccess()) {
				$model->setStatus(Application::STATUS_HAS_DB);
				$this->redirect(array('view', 'id' => $model->id));
			} else {
				$model->unsetStatus(Application::STATUS_HAS_DB);
			}
		}
		$this->render('configDb', array(
			'model' => $model,
			'wizard' => $wizard,
			'response' => $response,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Application');
		if (!Yii::app()->user->checkAccess('delete_application', array('application' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$response = null;
		if (isset($_POST['delete'])) {
			if (isset($_POST['opts'])) {
				$response = $model->cleanup(array_keys($_POST['opts']));
			}
			if (null === $response || $response->getIsSuccess()) {
				$model->delete();
				$this->redirect(array('index', 'project' => $model->project_id));
			}
		}
		$this->render('delete', array(
			'model' => $model,
			'response' => $response,
		));
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'Application');
		if (!Yii::app()->user->checkAccess('view_application', array('application' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$this->render('view', array(
			'model' => $model,
		));
	}
	
	public function actionPull($id)
	{
		$model = $this->loadModel($id, 'Application');
		if (!Yii::app()->user->checkAccess('pull_application', array('application' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$response = null;
		if (isset($_POST['make_pull'])) {
			set_time_limit(0);
			$response = $model->makePull();
		}
		$this->render('pull', array(
			'model' => $model,
			'response' => $response,
		));
	}
	
	protected function generatePassword($len=10)
	{
		$alpha = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789';
		$result = '';
		for ($i = 0; $i < $len; $i++) {
			$result .= substr($alpha, rand(0, 61), 1);
		}
		return $result;
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
				'actions' => array('create'),
				'roles' => array('create_application'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_application'),
			),
			array('allow',
				'actions' => array('update', 'configWeb', 'configGit', 'configDb'),
				'roles' => array('update_application'),
			),
			array('allow',
				'actions' => array('pull'),
				'roles' => array('pull_application'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_application'),
			),
			array('deny'),
		);
	}
}

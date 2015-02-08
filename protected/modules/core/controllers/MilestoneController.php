<?php

class MilestoneController extends AdminController 
{
	public function actionIndex($project)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('view_milestone', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = $this->createSearchModel('Milestone');
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
	
	public function actionQuery($query)
	{
		$project = Yii::app()->request->getQuery('project');
		$model = $this->createSearchModel('Milestone');
		$model->name = $query;
		$criteria = new CDbCriteria();
		$criteria->compare('milestone.project_id', $project);
		$criteria->limit = 15;
		$criteria->order = 'milestone.name';
		if (!Yii::app()->user->checkAccess('query_milestone', array('project' => '*'))) {
			$criteria->scopes = array('member');
		}
		$provider = $model->search($criteria);
		$results = array();
		foreach ($provider->getData() as $milestone) {
			$results[] = array(
				'id' => $milestone->id,
				'text' => $milestone->name,
			);
		}
		echo CJSON::encode($results);
	}
	
	public function actionCreate($project)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('create_milestone', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new Milestone('create');
		$model->project = $project;
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
			'project' => $project,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Milestone');
		if (!Yii::app()->user->checkAccess('update_milestone', array('milestone' => $model))) {
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
		$model = $this->loadModel($id, 'Milestone');
		if (!Yii::app()->user->checkAccess('delete_milestone', array('milestone' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'project' => $model->project_id));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'Milestone');
		if (!Yii::app()->user->checkAccess('view_milestone', array('milestone' => $model))) {
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
				'roles' => array('create_milestone'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_milestone'),
			),
			array('allow',
				'actions' => array('query'),
				'roles' => array('query_milestone'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_milestone'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_milestone'),
			),
			array('deny'),
		);
	}
}

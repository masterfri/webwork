<?php

class AssignmentController extends AdminController 
{
	public function actionIndex($project, $task=0)
	{
		$project = $this->loadModel($project, 'Project');
		$model = $this->createSearchModel('Assignment');
		$provider = $model->search(array(
			'condition' => 'project_id = :project_id',
			'params' => array(':project_id' => $project->id),
		));
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
			'project' => $project,
		));
	}
	
	public function actionCreate($project, $task=0)
	{
		$project = $this->loadModel($project, 'Project');
		$model = new Assignment('create');
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
		$model = $this->loadModel($id, 'Assignment');
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Assignment');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'project' => $model->project_id));
		}
	}
	
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id, 'Assignment'),
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
				'roles' => array('create_assignment'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_assignment'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_assignment'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_assignment'),
			),
			array('deny'),
		);
	}
}

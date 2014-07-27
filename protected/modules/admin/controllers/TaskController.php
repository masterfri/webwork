<?php

class TaskController extends AdminController 
{
	public function actionIndex($project, $milestone=null)
	{
		$project = $this->loadModel($project, 'Project');
		$model = $this->createSearchModel('Task');
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
		$model = new Task('create');
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
		$model = $this->loadModel($id, 'Task');
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Task');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'project' => $model->project_id));
		}
	}
	
	public function actionView($id)
	{
		$task = $this->loadModel($id, 'Task');
		$comment = new Comment('create');
		
		if (isset($_POST['Comment'])) {
			$comment->attributes = $_POST['Comment'];
			$comment->task = $task;
			if ($comment->save()) {
				$this->redirect(array('view', 'id' => $task->id, '#' => 'comment-' . $comment->id));
			}
		}
		
		$this->render('view', array(
			'model' => $task,
			'comment' => $comment,
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
				'roles' => array('create_task'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_task'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_task'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_task'),
			),
			array('deny'),
		);
	}
}

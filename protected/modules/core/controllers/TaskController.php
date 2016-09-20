<?php

class TaskController extends AdminController 
{
	public function actionIndex($project, $milestone=null)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('view_task', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'condition' => 'task.project_id = :project_id',
			'params' => array(':project_id' => $project->id),
		));
		if ($milestone) {
			$milestone = $this->loadModel($milestone, 'Milestone');
			$provider->criteria->compare('task.milestone_id', $milestone->id);
		}
		$this->render('index', array(
			'model' => $model,
			'project' => $project,
			'milestone' => $milestone,
			'provider' => $provider,
		));
	}
	
	public function actionQuery($query)
	{
		$project = Yii::app()->request->getQuery('project');
		$model = $this->createSearchModel('Task');
		$model->name = $query;
		$criteria = new CDbCriteria();
		$criteria->compare('task.project_id', $project);
		$criteria->limit = 15;
		$criteria->order = 'task.name';
		if (!Yii::app()->user->checkAccess('query_task', array('project' => '*'))) {
			$criteria->scopes = array('member');
		}
		$provider = $model->search($criteria);
		$results = array();
		foreach ($provider->getData() as $task) {
			$results[] = array(
				'id' => $task->id,
				'text' => $task->name,
			);
		}
		echo CJSON::encode($results);
	}
	
	public function actionCreate($project)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('create_task', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new Task('create');
		$model->project = $project;
		if ($this->saveModel($model)) {
			if($this->isAjax()) {
				$model->refresh();
				if ($model->user_subscription === null) {
					$subscription = $model->subscribe(Yii::app()->user->id);
				} else {
					$subscription = $model->user_subscription;
				}
				$subscription->markAsSeen();
				$this->ajaxSuccess(array(
					'trigger' => 'task.created',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Task has been created'),
					),
				));
			} else {
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Task has been created'));
				$this->redirect(array('view', 'id' => $model->id));
			}
		}
		$this->render('create', array(
			'model' => $model,
			'project' => $project,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('update_task', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Task has been updated'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionEstimate($id)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('estimate_task', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->setScenario('estimate');
		if ($this->saveModel($model)) {
			if($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'task.updated',
				));
			} else {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}
		if($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('estimate', array(
			'model' => $model,
		));
	}
	
	public function actionChangePriority($id, $priority)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('update_task_priority', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->setScenario('change-priority');
		$model->priority = $priority;
		$model->save();
		if($this->isAjax()) {
			$this->ajaxSuccess(array(
				'id' => $model->id,
			));
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view', 'id' => $model->id));
		}
	}
	
	public function actionChangeAssignment($id, $user)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('update_task_assignment', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->setScenario('change-assignment');
		$model->assigned_id = $user;
		if ($user > 0 && MysqlDateHelper::isEmpty($model->date_sheduled)) {
			$model->date_sheduled = MysqlDateHelper::currentDate();
		}
		$model->save();
		if($this->isAjax()) {
			$this->ajaxSuccess(array(
				'id' => $model->id,
			));
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view', 'id' => $model->id));
		}
	}
	
	public function actionChangeTags($id)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('update_task_tags', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->setScenario('change-tags');
		$model->tags = Yii::app()->request->getPost('tags');
		$model->save();
		if($this->isAjax()) {
			$this->ajaxSuccess(array(
				'id' => $model->id,
			));
		} else {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view', 'id' => $model->id));
		}
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('delete_task', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'project' => $model->project_id));
		}
	}
	
	public function actionView($id)
	{
		$task = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('view_task', array('task' => $task))) {
			throw new CHttpException(403, 'Forbidden');
		}
		
		if ($task->user_subscription !== null) {
			$last_visit = $task->user_subscription->last_view_time;
			$task->user_subscription->markAsSeen();
		} else {
			$last_visit = false;
		}
		
		if($this->isAjax()) {
			$this->layout = 'ajax';
		}
		
		$this->render('view', array(
			'model' => $task,
			'comment' => new Comment(),
			'last_visit' => $last_visit,
		));
	}
	
	public function actionComment($id)
	{
		$task = $this->loadModel($id, 'Task');
		$action = isset($_POST['action_type']) ? $_POST['action_type'] : Task::ACTION_COMMENT;
		if (!Yii::app()->user->checkAccess("{$action}_task", array('task' => $task))) {
			throw new CHttpException(403, 'Forbidden');
		}
		
		$comment = new Comment();
		
		if (isset($_POST['Comment'])) {
			$comment->setScenario($action);
			$comment->attributes = $_POST['Comment'];
			$comment->task = $task;
			$comment->action = $action;
			if ($comment->save()) {
				$task->doAction($action);
				if ($task->user_subscription === null) {
					$subscription = $task->subscribe(Yii::app()->user->id);
				} else {
					$subscription = $task->user_subscription;
				}
				if($this->isAjax()) {
					$comment->refresh();
					$subscription->markAsSeen();
					$this->ajaxSuccess(array(
						'trigger' => 'comment.created',
						'update' => array(
							array(
								'id' => 'comment-form-container',
								'content' => $this->renderPartial('_comment_form', array(
									'task' => $task,
									'comment' => new Comment(),
								), true),
							),
							array(
								'id' => 'comments-list',
								'method' => 'append',
								'content' => $this->renderPartial('_comments', array(
									'task' => $task,
									'comments' => array($comment),
									'last_visit' => false,
								), true),
							),
						),
					));
					Yii::app()->end();
				} else {
					$this->redirect(array('view', 'id' => $task->id, '#' => 'comment-' . $comment->id));
				}
			}
		}
		
		if($this->isAjax()) {
			$this->layout = 'ajax';
		}
		
		$this->render('comment', array(
			'model' => $task,
			'comment' => $comment,
		));
	}
	
	public function actionWatch($id)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('view_task', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->subscribe(Yii::app()->user->id);
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view', 'id' => $model->id));
		}
	}
	
	public function actionUnwatch($id)
	{
		$model = $this->loadModel($id, 'Task');
		if (!Yii::app()->user->checkAccess('view_task', array('task' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->unsubscribe(Yii::app()->user->id);
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('view', 'id' => $model->id));
		}
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
				'actions' => array('view', 'index', 'watch', 'unwatch', 'comment'),
				'roles' => array('view_task'),
			),
			array('allow',
				'actions' => array('query'),
				'roles' => array('query_task'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_task'),
			),
			array('allow',
				'actions' => array('changePriority'),
				'roles' => array('update_task_priority'),
			),
			array('allow',
				'actions' => array('changeAssignment'),
				'roles' => array('update_task_assignment'),
			),
			array('allow',
				'actions' => array('changeTags'),
				'roles' => array('update_task_tags'),
			),
			array('allow',
				'actions' => array('estimate'),
				'roles' => array('estimate_task'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_task'),
			),
			array('deny'),
		);
	}
}

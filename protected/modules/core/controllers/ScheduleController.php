<?php

class ScheduleController extends AdminController
{
	public function actionIndex($start=null, $project=null)
	{
		if (null === $start) {
			$w = date('N') - 1;
			$start = date('Y-m-d', strtotime("-{$w} days"));
		}
		$user = Yii::app()->user;
		$model = $this->createSearchModel('TaskSchedule');
		$show_all_users = $user->checkAccess('view_schedule', array('user' => '*'));
		$params = array();
		if (null !== $project) {
			$project = $this->loadModel($project, 'Project');
			if (!$user->checkAccess('view_schedule', array('project' => $project))) {
				throw new CHttpException(403, 'Forbidden');
			}
			$params['condition'] = 'task.project_id = :project';
			$params['params'][':project'] = $project->id;
		}
		if (!$show_all_users) {
			$params['scopes'] = array('my');
		}
		$data = $model->getWeek($start, $params);
		if($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('index', array(
			'data' => $data,
			'start' => $start,
			'show_all_users' => $show_all_users,
			'project' => $project,
		));
	}
	
	public function actionUpdate($project, $start=null)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('update_schedule', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		
		if (null === $start) {
			$w = date('N') - 1;
			$start = date('Y-m-d', strtotime("-{$w} days"));
		}
		
		$data = $this->getProjectScheduleData($start, $project);
		$tasks = $this->getTaskList($project);

		if($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('update', array(
			'data' => $data,
			'start' => $start,
			'project' => $project,
			'tasks' => $tasks,
		));
	}
	
	public function actionTasklist($project)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('update_schedule', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$tasks = $this->getTaskList($project);
		$this->renderPartial('_tasklist', array(
			'project' => $project,
			'tasks' => $tasks,
		));
	}
	
	public function actionPut($task, $date, $user)
	{
		$task = $this->loadModel($task, 'Task');
		$project = $task->project;
		if (!Yii::app()->user->checkAccess('update_schedule', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!$project->isUserAssigned($user)) {
			throw new CHttpException(403, 'Forbidden');
		}
		
		$task->setScenario('schedule');
		$task->assigned_id = $user;
		$task->date_sheduled = $date;
		if (isset($_POST['Task'])) {
			$task->attributes = $_POST['Task'];
		}
		
		if ($task->save()) {
			$w = date('N', strtotime($date)) - 1;
			$start = date('Y-m-d', strtotime("{$date} -{$w} days"));
			$data = $this->getProjectScheduleData($start, $project);
			$tasks = $this->getTaskList($project);
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'task.planned',
					'update' => array(
						array(
							'id' => 'scheduling-grid',
							'content' => $this->renderPartial('_grid', array(
								'data' => $data,
								'start' => $start,
								'project' => $project,
							), true),
						),
						array(
							'id' => 'tasklist',
							'content' => $this->renderPartial('_tasklist', array(
								'project' => $project,
								'tasks' => $tasks,
							), true),
						),
					),
				));
			} else {
				$this->redirect(array('update', 'project' => $project->id, 'start' => $start));
			}
		} else {
			if ($this->isAjax()) {
				if (!isset($_POST['Task'])) {
					$task->clearErrors();
					$this->ajaxSuccess(array(
						'modal' => array(
							'title' => Yii::t('core.crud', 'Task Estimation'),
							'content' => $this->renderPartial('../task/_estimate_form', array(
								'model' => $task,
							), true),
						),
					));
				} else {
					$this->renderPartial('../task/_estimate_form', array(
						'model' => $task,
					));
				}
			} else {
				$this->redirect(array('task/estimate', 'id' => $task->id));
			}
		}
	}
	
	public function actionReset($task)
	{
		$task = $this->loadModel($task, 'Task');
		$project = $task->project;
		if (!Yii::app()->user->checkAccess('update_schedule', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		
		$date = $task->date_sheduled;
		$task->date_sheduled = '';
		$task->update(array('date_sheduled'));
		
		$w = date('N', strtotime($date)) - 1;
		$start = date('Y-m-d', strtotime("{$date} -{$w} days"));
		$data = $this->getProjectScheduleData($start, $project);
		$tasks = $this->getTaskList($project);

		if ($this->isAjax()) {
			$this->ajaxSuccess(array(
				'trigger' => 'task.reset',
				'update' => array(
					array(
						'id' => 'scheduling-grid',
						'content' => $this->renderPartial('_grid', array(
							'data' => $data,
							'start' => $start,
							'project' => $project,
						), true),
					),
					array(
						'id' => 'tasklist',
						'content' => $this->renderPartial('_tasklist', array(
							'project' => $project,
							'tasks' => $tasks,
						), true),
					),
				),
			));
		} else {
			$this->redirect(array('update', 'project' => $project->id, 'start' => $start));
		}
	}
	
	protected function getProjectScheduleData($start, $project)
	{
		$model = $this->createSearchModel('TaskSchedule');
		$user_ids = array_map(function($a) { return $a->user_id; }, $project->assignments);
		$criteria = new CDbCriteria();
		$criteria->addInCondition('taskSchedule.user_id', $user_ids);
		$data = $model->getWeek($start, $criteria);
		foreach ($project->assignments as $assignment) {
			if (!isset($data['hr'][$assignment->user_id])) {
				$data['hr'][$assignment->user_id] = $assignment->user;
				$data['grid'][$assignment->user_id] = array();
			}
		}
		return $data;
	}
	
	protected function getTaskList($project)
	{
		$criteria = new CDbCriteria();
		$criteria->scopes = array('nonplanned');
		$criteria->compare('task.project_id', $project->id);
		$model = $this->createSearchModel('Task');
		$provider = $model->search($criteria);
		return $provider;
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
				'actions' => array('index'),
				'roles' => array('view_schedule'),
			),
			array('allow',
				'actions' => array('update', 'tasklist', 'put', 'reset'),
				'roles' => array('update_schedule'),
			),
			array('deny'),
		);
	}
}

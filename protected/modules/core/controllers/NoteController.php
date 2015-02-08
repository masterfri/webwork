<?php

class NoteController extends AdminController 
{
	public function actionCreate($project)
	{
		$project = $this->loadModel($project, 'Project');
		if (!Yii::app()->user->checkAccess('create_note', array('project' => $project))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new Note('create');
		$model->project = $project;
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'note.created',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Note has been created'),
					),
				));
			} else {
				$this->redirect(array('project/view', 'id' => $project->id));
			}
		}
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('create', array(
			'model' => $model,
			'project' => $project,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Note');
		if (!Yii::app()->user->checkAccess('update_note', array('note' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'note.updated',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Note has been updated'),
					),
				));
			} else {
				$this->redirect(array('project/view', 'id' => $model->project_id));
			}
		}
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Note');
		if (!Yii::app()->user->checkAccess('delete_note', array('note' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if ($this->isAjax()) {
			$this->ajaxSuccess(array(
				'trigger' => 'note.deleted',
			));
		} else {
			$this->redirect(array('project/view', 'id' => $model->project_id));
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
				'roles' => array('create_note'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_note'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_note'),
			),
			array('deny'),
		);
	}
}

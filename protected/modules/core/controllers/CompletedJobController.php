<?php

class CompletedJobController extends AdminController 
{
	public function actionCreate($report)
	{
		$report = $this->loadModel($report, 'CompletionReport');
		$model = new CompletedJob('create');
		$model->report_id = $report->id;
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'completedjob.created',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Work has been added'),
					),
				));
			} else {
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Work has been added'));
				$this->redirect(array('completionReport/update', 'id' => $invoice->id));
			}
		}
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'CompletedJob');
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'completedjob.updated',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Work has been updated'),
					),
				));
			} else {
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Work has been updated'));
				$this->redirect(array('completionReport/update', 'id' => $model->report_id));
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
		$model = $this->loadModel($id, 'CompletedJob');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('completionReport/update', 'id' => $model->report_id));
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
				'roles' => array('update_completion_report'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_completion_report'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('update_completion_report'),
			),
			array('deny'),
		);
	}
}

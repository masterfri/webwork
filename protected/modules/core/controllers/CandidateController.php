<?php

class CandidateController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Candidate');
		$provider = $model->search();
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionCreate()
	{
		$model = new Candidate('create');
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Candidate has been created'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Candidate');
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Candidate has been updated'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Candidate');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionReset($id)
	{
		$model = $this->loadModel($id, 'Candidate');
		$model->resetExaminationResults();
		if(!isset($_GET['ajax'])) {
			$this->redirect(array('view', 'id' => $model->id));
		}
	}
	
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id, 'Candidate'),
		));
	}

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete, reset', 
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',
				'actions' => array('create'),
				'roles' => array('create_candidate'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_candidate'),
			),
			array('allow',
				'actions' => array('update', 'reset'),
				'roles' => array('update_candidate'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_candidate'),
			),
			array('deny'),
		);
	}
}

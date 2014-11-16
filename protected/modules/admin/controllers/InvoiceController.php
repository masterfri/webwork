<?php

class InvoiceController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Invoice');
		$provider = $model->search();
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionCreate()
	{
		$model = new Invoice('create');
		$model->from_id = Yii::app()->user->id;
		if ($this->saveModel($model)) {
			$this->redirect(array('update', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Invoice');
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Invoice');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id, 'Invoice'),
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
				'roles' => array('create_invoice'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_invoice'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_invoice'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_invoice'),
			),
			array('deny'),
		);
	}
}

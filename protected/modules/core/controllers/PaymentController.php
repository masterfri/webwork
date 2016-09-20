<?php

class PaymentController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Payment');
		$provider = $model->search();
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionCreate($invoice)
	{
		$invoice = $this->loadModel($invoice, 'Invoice');
		$model = new Payment('create');
		$model->invoice = $invoice;
		$model->amount = max(0.01, $invoice->amount - $invoice->payd);
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Payment has been created'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
			'invoice' => $invoice,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Payment');
		if ($this->saveModel($model)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Payment has been updated'));
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Payment');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id, 'Payment'),
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
				'roles' => array('create_payment'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_payment'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_payment'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_payment'),
			),
			array('deny'),
		);
	}
}

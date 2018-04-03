<?php

class InvoiceItemController extends AdminController 
{
	public function actionCreate($invoice)
	{
		$invoice = $this->loadModel($invoice, 'Invoice');
		$model = new InvoiceItem('create');
		$model->invoice_id = $invoice->id;
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'invoiceitem.created',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Item has been added'),
					),
				));
			} else {
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Item has been added'));
				$this->redirect(array('invoice/update', 'id' => $invoice->id));
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
		$model = $this->loadModel($id, 'InvoiceItem');
		$model->bonus = 0;
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'invoiceitem.updated',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Item has been updated'),
					),
				));
			} else {
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Item has been updated'));
				$this->redirect(array('invoice/update', 'id' => $model->invoice_id));
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
		$model = $this->loadModel($id, 'InvoiceItem');
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('invoice/update', 'id' => $model->invoice_id));
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
				'roles' => array('update_invoice'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_invoice'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('update_invoice'),
			),
			array('deny'),
		);
	}
}

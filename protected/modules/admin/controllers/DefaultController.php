<?php

class DefaultController extends AdminController
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'assigned',
				'scheduled',
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionUpdated()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'member', 
				'updated',
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('updated', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionNew()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'member', 
				'new',
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('new', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionMy()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'my', 
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('my', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionPending()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'member', 
				'pending',
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('pending', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionExpired()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'member', 
				'expired',
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('expired', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionCompleted()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'member', 
				'closed',
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('completed', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionHold()
	{
		$model = $this->createSearchModel('Task');
		$provider = $model->search(array(
			'scopes' => array(
				'member', 
				'on_hold',
			),
		));
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('on_hold', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionLogin()
	{
		$model = new UserLoginForm();
		
		if(isset($_POST['UserLoginForm'])) {
			$model->attributes = $_POST['UserLoginForm'];
			if($model->validate() && $model->login()) {
				$this->redirect(Yii::app()->createUrl('/admin'));
			}
		}
		
		$this->layout = 'admin.views.layouts.login';
		$this->render('login', array(
			'model' => $model,
		));
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionError()
	{
	    if($error = Yii::app()->errorHandler->error) {
	    	if(Yii::app()->request->isAjaxRequest) {
	    		echo $error['message'];
	    	} else {
				$this->layout = 'admin.views.layouts.error';
	        	$this->render('error', $error);
			}
	    }
	}
	
	public function accessRules()
	{
		return array(
			array('allow',
				'users' => array('@'),
			),
			array('allow',
				'actions' => array('login', 'logout', 'error'),
			),
			array('deny'),
		);
	}
}

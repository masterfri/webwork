<?php

class DefaultController extends AdminController
{
	protected $emptyModel;
	
	public function actionIndex()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataScheduled($model);
		$provider->sort->defaultOrder = 'date_sheduled';
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionUpdated()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataUpdated($model);
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('updated', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionNew()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataNew($model);
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('new', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionMy()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataMy($model);
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('my', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionPending()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataPending($model);
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('pending', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionExpired()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataExpired($model);
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('expired', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionCompleted()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataCompleted($model);
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('completed', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionHold()
	{
		$model = $this->createSearchModel('Task');
		$provider = $this->getDataOnHold($model);
		$this->layout = 'admin.views.layouts.dashboard';
		$this->render('on_hold', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function getDataScheduled($model=null)
	{
		return $this->getData(array(
			'assigned',
			'scheduled',
		), $model);
	}
	
	public function getDataUpdated($model=null)
	{
		return $this->getData(array(
			'member', 
			'updated',
		), $model);
	}
	
	public function getDataNew($model=null)
	{
		return $this->getData(array(
			'member', 
			'new',
		), $model);
	}
	
	public function getDataMy($model=null)
	{
		return $this->getData(array(
			'my',
		), $model);
	}
	
	public function getDataPending($model=null)
	{
		return $this->getData(array(
			'member', 
			'pending',
		), $model);
	}
	
	public function getDataExpired($model=null)
	{
		return $this->getData(array(
			'member', 
			'expired',
		), $model);
	}
	
	public function getDataCompleted($model=null)
	{
		return $this->getData(array(
			'member', 
			'closed',
		), $model);
	}
	
	public function getDataOnHold($model=null)
	{
		return $this->getData(array(
			'member', 
			'on_hold',
		), $model);
	}
	
	protected function getData($scopes, $model=null)
	{
		if (null === $model) {
			$model = $this->getEmptyModel();
		}
		return $model->search(array(
			'scopes' => $scopes,
		));
	}
	
	protected function getEmptyModel()
	{
		if (null === $this->emptyModel) {
			$this->emptyModel = new Task();
			$this->emptyModel->unsetAttributes();
		}
		return $this->emptyModel;
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

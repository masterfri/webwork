<?php

class TagController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Tag');
		$criteria = new CDbCriteria();
		if (Yii::app()->user->checkAccess('view_tag', array('project' => '*'))) {
			$criteria->scopes = array('active');
		} else {
			$criteria->scopes = array('member', 'active');
		}
		$provider = $model->search($criteria);
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionQuery($query)
	{
		$project = Yii::app()->request->getQuery('project');
		$model = $this->createSearchModel('Tag');
		$model->name = $query;
		$criteria = new CDbCriteria();
		if (!empty($project)) {
			$criteria->compare('tag.project_id', $project);
			$criteria->addCondition('tag.project_id = 0', 'OR');
		}
		$criteria->limit = 15;
		$criteria->order = 'tag.name';
		if (!Yii::app()->user->checkAccess('query_tag', array('project' => '*'))) {
			$criteria->scopes = array('member');
		}
		$provider = $model->search($criteria);
		$results = array();
		foreach ($provider->getData() as $tag) {
			$results[] = array(
				'id' => $tag->id,
				'text' => $tag->name,
			);
		}
		echo CJSON::encode($results);
	}
	
	public function actionCreate()
	{
		if (Yii::app()->user->checkAccess('create_tag', array('project' => '*'))) {
			$model = new Tag('create');
		} else {
			$model = new Tag('createShared');
		}
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('create', array(
			'model' => $model,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'Tag');
		if (!Yii::app()->user->checkAccess('update_tag', array('tag' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (Yii::app()->user->checkAccess('update_tag', array('project' => '*'))) {
			$model->setScenario('update');
		} else {
			$model->setScenario('updateShared');
		}
		if ($this->saveModel($model)) {
			$this->redirect(array('view', 'id' => $model->id));
		}
		$this->render('update', array(
			'model' => $model,
		));
	}
	
	public function actionDelete($id)
	{
		$model = $this->loadModel($id, 'Tag');
		if (!Yii::app()->user->checkAccess('delete_tag', array('tag' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'Tag');
		if (!Yii::app()->user->checkAccess('view_tag', array('tag' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$this->render('view', array(
			'model' => $model,
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
				'roles' => array('create_tag'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('view_tag'),
			),
			array('allow',
				'actions' => array('query'),
				'roles' => array('query_tag'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('update_tag'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('delete_tag'),
			),
			array('deny'),
		);
	}
}

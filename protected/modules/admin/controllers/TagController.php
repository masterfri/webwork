<?php

class TagController extends AdminController 
{
	public function actionIndex()
	{
		$model = $this->createSearchModel('Tag');
		$provider = $model->search();
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionQuery($query, $project='')
	{
		$model = $this->createSearchModel('Tag');
		$model->name = $query;
		$criteria = new CDbCriteria();
		$criteria->compare('project_id', $project);
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
		$model = new Tag('create');
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
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
		}
	}
	
	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id, 'Tag'),
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

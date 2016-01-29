<?php

class AppEntityController extends AdminController 
{
	public function actionIndex($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = $this->createSearchModel('AppEntity');
		$provider = $model->search(array(
			'condition' => 'application_id = :application_id',
			'params' => array(':application_id' => $application->id),
		));
		$this->render('index', array(
			'model' => $model,
			'provider' => $provider,
			'application' => $application,
		));
	}
	
	public function actionCreate($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new AppEntity('create');
		$model->application = $application;
		$model->application_id = $application->id;
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'appEntity.created',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Application entity has been created'),
					),
				));
			} else {
				$this->redirect(array('view', 'id' => $model->id));
			}
		}
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('create', array(
			'model' => $model,
			'application' => $application,
		));
	}
	
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'appEntity.updated',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Application entity has been updated'),
					),
				));
			} else {
				$this->redirect(array('view', 'id' => $model->id));
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
		$model = $this->loadModel($id, 'AppEntity');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'application' => $model->application->id));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$this->render('view', array(
			'model' => $model,
		));
	}
	
	public function actionBuild($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		
		$entities = CHtml::listData($application->entities, 'id', 'name');
		$cf = $application->getCF();
		$schemes = $cf->getBuildSchemes();
		$schemes = array_combine($schemes, $schemes);
		$packages = $cf->getPackages();
		
		$application->setScenario('build');
		
		if (empty($application->last_build_json)) {
			$application->last_build_json = CJSON::encode(array(
				'entities' => array_keys($entities),
				'schemes' => array(),
				'packages' => array(),
			));
		}
		
		$current_packages = $application->getPackages();
		
		if ($this->saveModel($application)) {
			$to_build_ids = $application->getEntitiesToBuild();
			$new_packages = array_diff($application->getPackages(), $current_packages);
			$to_build = array_filter($application->entities, function($e) use($to_build_ids) {
				return in_array($e->id, $to_build_ids);
			});
			$result = Yii::t('core.crud', 'Build started') . "\n";
			foreach ($new_packages as $package) {
				$result .= Yii::t('core.crud', 'Deploying package') . ': ' . $package . "\n";
				$cf->deployPackage($package);
			}
			$result .= $cf->build($to_build, $application->getSchemesToBuild(), $application->getBuildOptions());
			$result .= Yii::t('core.crud', 'Build finished');
			$this->render('build-results', array(
				'application' => $application,
				'result' => $result,
			));
		} else {
			$this->render('build', array(
				'application' => $application,
				'entities' => $entities,
				'schemes' => $schemes,
				'packages' => $packages,
			));
		}
	}
	
	public function actionPush($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('push_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!($application->status & Application::STATUS_HAS_GIT)) {
			throw new CHttpException(500, 'Application is not configured');
		}
		
	}
	
	public function actionDownload($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$file = Yii::app()->getRuntimePath() . '/' . uniqid() . '.zip';
		$zip = new ZipArchive(); 
		$zip->open($file, ZipArchive::CREATE);
		try {
			$added = array();
			$this->writeToZip($zip, $application->getCFWorkdir() . '/compiled/', '', $added);
			$this->writeToZip($zip, $application->getCFWorkdir() . '/static/', '', $added);
			$zip->close();
			header('Content-Type: application/zip');
			header('Content-Size: ' . filesize($file));
			header('Content-Disposition: attachment; filename=app-' . $application->id . '.zip');
			readfile($file);
			@unlink($file);
			exit;
		} catch (Exception $e) {
			$zip->close();
			@unlink($file);
			throw $e;
		}
	}
	
	protected function writeToZip($zip, $dir, $path, &$added)
	{
		if (is_dir($dir)) {
			if ($dh = @opendir($dir)) { 
				if(!empty($path) && !array_key_exists($path, $added)) {
					if (!$zip->addEmptyDir($path)) {
						throw new CException('Can not create directory in archive: ' . $path);
					}
					$added[$path] = 1;
				}
				while (($file = readdir($dh)) !== false) {
					if (!is_file($dir . $file)) {
						if ($file !== '.' && $file !== '..') {
							$this->writeToZip($zip, $dir . $file . '/', $path . $file . '/', $added);
						}
					} else { 
						if (!$zip->addFile($dir . $file, $path . $file)) {
							throw new CException('Can not add file to archive: ' . $path . $file);
						}
					}
				}
			} else {
				throw new CException('Can not access directory: ' . $dir);
			}
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
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('view', 'index'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('build', 'download'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('push'),
				'roles' => array('push_application'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('design_application'),
			),
			array('deny'),
		);
	}
}

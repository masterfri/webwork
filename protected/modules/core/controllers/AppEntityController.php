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
	
	public function actionTemplates()
	{
		$model = $this->createSearchModel('AppEntity');
		$provider = $model->search(array(
			'condition' => 'application_id = 0',
		));
		$this->render('templates', array(
			'model' => $model,
			'provider' => $provider,
		));
	}
	
	public function actionChooseTemplate($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = $this->createSearchModel('AppEntity');
		$provider = $model->search(array(
			'condition' => 'application_id = 0',
		));
		$this->render('chooseTemplate', array(
			'model' => $model,
			'provider' => $provider,
			'application' => $application,
		));
	}
	
	public function actionCreate($application, $template=null)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model = new AppEntity('create');
		$model->application = $application;
		$model->application_id = $application->id;
		if ($template !== null) {
			$template = $this->loadModel($template, 'AppEntity');
			$model->expert_mode = $template->expert_mode;
			if ($template->expert_mode == 1) {
				$model->setScenario('createExpert');
				$model->plain_source = $template->plain_source;
			} else {
				$model->name = $template->name;
				$model->module = $template->module;
				$model->label = $template->label;
				$model->description = $template->description;
				$model->json_schemes = $template->json_schemes;
				$model->json_source = $template->json_source;
			}
		}
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
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Application entity has been created'));
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
		if ($model->application === null) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if ($model->expert_mode == 1) {
			$model->setScenario('updateExpert');
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
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Application entity has been updated'));
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
		if ($model->application === null) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index', 'application' => $model->application->id));
		}
	}
	
	public function actionExpertMode($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if ($model->application === null) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		if ($model->expert_mode == 0) {
			$model->updateByPk($model->id, array(
				'expert_mode' => 1,
			));
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Expert mode has been engaged'));
		}
		$this->redirect(array('view', 'id' => $model->id));
	}
	
	public function actionUpdateTemplate($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if ($model->application !== null) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!Yii::app()->user->checkAccess('update_entity_template', array('template' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->setScenario('updateTemplate');
		if ($this->saveModel($model)) {
			if ($this->isAjax()) {
				$this->ajaxSuccess(array(
					'trigger' => 'appEntity.updated',
					'message' => array(
						'title' => Yii::t('core.crud', 'Success'),
						'text' => Yii::t('core.crud', 'Template has been updated'),
					),
				));
			} else {
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Template has been updated'));
				$this->redirect(array('view', 'id' => $model->id));
			}
		}
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		$this->render('updateTemplate', array(
			'model' => $model,
		));
	}
	
	public function actionDeleteTemplate($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if ($model->application !== null) {
			throw new CHttpException(403, 'Forbidden');
		}
		if (!Yii::app()->user->checkAccess('delete_entity_template', array('template' => $model))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$model->delete();
		if(!isset($_GET['ajax'])) {
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('templates'));
		}
	}
	
	public function actionView($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if ($model->application !== null) {
			if (!Yii::app()->user->checkAccess('design_application', array('application' => $model->application))) {
				throw new CHttpException(403, 'Forbidden');
			}
			$this->render('view', array(
				'model' => $model,
			));
		} else {
			$this->render('view-tpl', array(
				'model' => $model,
			));
		}
	}
	
	public function actionCopyAsTemplate($id)
	{
		$model = $this->loadModel($id, 'AppEntity');
		if (!Yii::app()->user->checkAccess('create_entity_template')) {
			throw new CHttpException(403, 'Forbidden');
		}
		$template = new AppEntity('copyAsTemplate');
		$template->name = $model->name;
		$template->module = $model->module;
		$template->label = empty($model->label) ? $model->name : $model->label;
		$template->description = $model->description;
		$template->expert_mode = $model->expert_mode;
		if ($model->expert_mode == 1) {
			$template->plain_source = $model->plain_source;
		} else {
			$template->json_schemes = $model->json_schemes;
			$template->json_source = $model->json_source;
		}
		if ($this->saveModel($template)) {
			Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Template has been created'));
			$this->redirect(array('view', 'id' => $template->id));
		}
		$this->render('copyAsTemplate', array(
			'model' => $model,
			'template' => $template,
		));
	}
	
	public function actionBuild($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		
		$entities = $application->getEntitiesList();
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
			$start_time = microtime(true);
			$result .= $cf->build($to_build, $application->getSchemesToBuild(), $application->getBuildOptions());
			$end_time = microtime(true);
			$stats = $cf->getLayer()->getCacheStats();
			$result .= Yii::t('core.crud', 'Build finished') . "\n";
			$result .= Yii::t('core.crud', 'Time: {time} ms, cache puts: {puts}, cache hits: {hits}', array(
				'{time}' => number_format(($end_time - $start_time) * 1000, 3),
				'{puts}' => $stats['puts'],
				'{hits}' => $stats['hits'],
			));
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
	
	public function actionCleanup($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$response = null;
		if (isset($_POST['cleanup'])) {
			if (isset($_POST['git'])) {
				$response = $application->cleanup(array('tmpgit'));
			}
			if (null === $response || $response->getIsSuccess()) {
				if (isset($_POST['build'])) {
					if ($_POST['build'] == 'compiled') {
						$application->getCF()->cleanup();
					} else {
						$application->getCF()->cleanup(false);
						$application->last_build_json = new CDbExpression('NULL');
						$application->save(false);
					}
				}
				Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Application has been cleaned up'));
				$this->redirect(array('index', 'application' => $application->id));
			}
		}
		
		$this->render('cleanup', array(
			'application' => $application,
		));
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
		
		$model = new PushApplicationForm();
		$model->branch = $application->git_branch;
		$response = null;
		if (isset($_POST['PushApplicationForm'])) {
			$model->attributes = $_POST['PushApplicationForm'];
			if ($model->validate()) {
				$response = $application->pullWorkCopy($model->branch);
				if ($response->getIsSuccess()) {
					if ($model->hasConflictedFiles()) {
						$ignore = $model->getResolutionFiles(PushApplicationForm::RESOLVE_IGNORE);
						$application->getCF()->updateIgnoreList($ignore);
						$response = $application->releaseWorkCopy(true);
					} else {
						$response = $application->releaseWorkCopy();
					}
					if ($response->getIsSuccess()) {
						$response = $application->pushWorkCopy($model->branch, $model->message);
						if ($response->getIsSuccess()) {
							Yii::app()->user->setFlash('message', Yii::t('core.crud', 'Application has been pushed successfully'));
							$this->redirect(array('index', 'application' => $application->id));
						}
					} elseif ($response->getCode() == HttpShResponse::CODE_ERR_CF_CONFLICTS) {
						$model->setConflictedFiles($response->getData('conflict'));
						$response = null;
					}
					
					
				}
			}
		}
		
		$this->render('push', array(
			'application' => $application,
			'model' => $model,
			'response' => $response,
		));
	}
	
	public function actionGraph($application)
	{
		$application = $this->loadModel($application, 'Application');
		if (!Yii::app()->user->checkAccess('design_application', array('application' => $application))) {
			throw new CHttpException(403, 'Forbidden');
		}
		$this->render('graph', array(
			'application' => $application,
		));
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
			$this->writeToZip($zip, $application->getCFWorkdir() . '/' . CodeforgeComponent::PROJECT_DIR_NAME . '/compiled/', '', $added);
			$this->writeToZip($zip, $application->getCFWorkdir() . '/' . CodeforgeComponent::PROJECT_DIR_NAME . '/static/', '', $added);
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
			'postOnly + delete, expertMode', 
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
				'actions' => array('view', 'index', 'templates', 'chooseTemplate', 'graph'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('update'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('updateTemplate'),
				'roles' => array('update_entity_template'),
			),
			array('allow',
				'actions' => array('build', 'download', 'cleanup'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('push'),
				'roles' => array('push_application'),
			),
			array('allow',
				'actions' => array('copyAsTemplate'),
				'roles' => array('create_entity_template'),
			),
			array('allow',
				'actions' => array('delete'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('expertMode'),
				'roles' => array('design_application'),
			),
			array('allow',
				'actions' => array('deleteTemplate'),
				'roles' => array('delete_entity_template'),
			),
			array('deny'),
		);
	}
}

<?php

$this->pageHeading = Yii::t('core.crud', 'Application Entity Updating');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->application->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->application->project)) ? array('project/view', 'id' => $model->application->project->id) : false, 
	CHtml::encode($model->application->name) => Yii::app()->user->checkAccess('view_application', array('application' => $model->application)) ? array('application/view', 'id' => $model->application->id) : false, 
	Yii::t('core.crud', 'Application Entities') => Yii::app()->user->checkAccess('design_application', array('application' => $model->application)) ? array('index', 'application' => $model->application->id) : false, 
	Yii::t('core.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Application Entity'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'application' => $model->application->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $model->application)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'View Application Entity'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $model->application)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Application Entities'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'application' => $model->application->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $model->application)),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" data-marker="ajax-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div data-marker="ajax-body">
			<?php $this->renderPartial('_form', array(
				'model' => $model,
			)); ?>
		</div>
	</div>
</div>

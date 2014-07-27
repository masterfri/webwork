<?php

$this->pageHeading = Yii::t('admin.crud', 'Updating Task');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project') ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Task') => Yii::app()->user->checkAccess('view_task') ? array('index', 'project' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Task'), 
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ' . Yii::t('admin.crud', 'View Task'), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Task'), 
		'url'=>array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_task'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form', array(
			'model' => $model,
		)); ?>
	</div>
</div>

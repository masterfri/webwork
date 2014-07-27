<?php

$this->pageHeading = Yii::t('admin.crud', 'New Assignment');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project') ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('admin.crud', 'Assignment') => Yii::app()->user->checkAccess('view_assignment') ? array('index', 'project' => $project->id) : false, 
	Yii::t('admin.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Assignment'), 
		'url' => array('index', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('view_assignment'),
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

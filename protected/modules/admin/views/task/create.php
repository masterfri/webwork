<?php

$this->pageHeading = Yii::t('admin.crud', 'New Task');


$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $project)) ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('admin.crud', 'Tasks') => Yii::app()->user->checkAccess('view_task', array('project' => $project)) ? array('index', 'project' => $project->id) : false, 
	Yii::t('admin.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Tasks'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('project' => $project)),
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
			'project' => $project,
		)); ?>
	</div>
</div>

<?php

$this->pageHeading = Yii::t('admin.crud', 'Assignment Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Assignment') => Yii::app()->user->checkAccess('view_assignment', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Assignment'), 
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_assignment', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Assignment'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_assignment', array('assignment' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Assignment'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this assignment?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_assignment', array('assignment' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Assignment'), 
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_assignment', array('project' => $model->project)),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'user',
			array('name' => 'role', 'value' => $model->getRoleName()),
		),
	)); ?>
</div>

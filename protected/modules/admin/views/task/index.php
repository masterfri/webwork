<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Task');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project') ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('admin.crud', 'Task'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Task'), 
		'url' => array('create', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('create_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i> ' . Yii::t('admin.crud', 'Search'), 
		'url' => '#',
		'linkOptions' => array(
			'class' => 'search-button',
			'data-toggle' => 'search-form',
		),
	),
);

?>

<div class="panel panel-default search-form" style="display: none;">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('admin.crud', 'Search'); ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_search',array(
			'model' => $model,
		)); ?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'task-grid',
		'dataProvider' => $provider,
		'columns' => array(
			'name',
			array(
				'name' => 'priority',
				'value' => '$data->getPriority()',
			),
			'date_sheduled:date',
			'due_date:date',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this task?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_task') ? '{update}' : '').
					(Yii::app()->user->checkAccess('delete_task') ? '{delete}' : ''),
			),
		),
	)); ?>
</div>

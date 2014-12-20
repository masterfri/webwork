<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Time Entries');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Time Entries'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Time Entry'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-time"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Daily Time Report'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('daily'),
		'visible' => Yii::app()->user->checkAccess('daily_time_report'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-calendar"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Monthly Time Report'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('monthly'),
		'visible' => Yii::app()->user->checkAccess('monthly_time_report'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i>', 
		'url' => '#',
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Search'), 
			'class' => 'search-button btn btn-default',
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
		'id' => 'timeentry-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array(
				'class' => 'LinkColumn', 
				'name' => 'project',
				'linkExpression' => 'array("project/view", "id" => $data->project_id)',
				'activityExpression' => 'Yii::app()->user->checkAccess("view_project", array("project" => $data->project))',
			),
			array(
				'class' => 'LinkColumn', 
				'name' => 'task',
				'linkExpression' => 'array("task/view", "id" => $data->task_id)',
				'activityExpression' => 'Yii::app()->user->checkAccess("view_task", array("task" => $data->task))',
			),
			'user',
			'activity',
			'description',
			'amount:hours',
			'date_created:datetime',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this time entry?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_time_entry", array("entry" => $data))',
					),
					'delete' => array(
						'visible' => 'Yii::app()->user->checkAccess("delete_time_entry", array("entry" => $data))',
					),
				),
			),
		),
	)); ?>
</div>

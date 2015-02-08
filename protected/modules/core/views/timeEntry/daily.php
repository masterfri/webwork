<?php

$this->pageHeading = Yii::t('core.crud', 'Daily Time Report');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Time Entries') => Yii::app()->user->checkAccess('view_time_entry') ? array('index') : false, 
	Yii::t('core.crud', 'Daily Time Report'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-calendar"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Monthly Time Report'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('monthly'),
		'visible' => Yii::app()->user->checkAccess('monthly_time_report'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Time Entries'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i>', 
		'url' => '#',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Search'), 
			'class' => 'search-button btn btn-default',
			'data-toggle' => 'search-form',
		),
	),
);

?>

<div class="panel panel-default search-form" style="display: none;">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Search'); ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_search_daily',array(
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
		'template' => '{items} <div class="table-totals">' . Yii::t('core.crud', 'Total') . ': <span>' . Yii::app()->format->formatHours($sum) . '</span></div> {pager}',
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
			array(
				'name' => 'user',
				'visible' => Yii::app()->user->checkAccess('view_time_entry', array('entry' => '*')),
			),
			'activity',
			'description',
			'amount:hours',
			'date_created:time',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this time entry?'),
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

<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Milestones');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $project)) ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('core.crud', 'Milestones'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Milestone'),
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('create_milestone', array('project' => $project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Project'),
			'class' => 'btn btn-default',
		),
		'url' => array('project/view', 'id' => $project->id),
		'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $project)),
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
		'id' => 'milestone-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array(
				'class' => 'LinkColumn', 
				'name' => 'name'
			),
			'date_start:date',
			'due_date:date',
			array(
				'name' => 'count_tasks',
				'value' => 'CHtml::link(ViewHelper::progerss($data->count_tasks, $data->count_closed_tasks), array("task/index", "milestone" => $data->id, "project" => $data->project_id), array("class" => "progress-container"))',
				'type' => 'raw',
				'visible' => Yii::app()->user->checkAccess('view_task'),
			),
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this milestone?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_milestone", array("milestone" => $data))',
					),
					'delete' => array(
						'visible' => 'Yii::app()->user->checkAccess("delete_milestone", array("milestone" => $data))',
					),
				),
			),
		),
	)); ?>
</div>

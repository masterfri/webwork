<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Projects');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Project'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_project'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-folder-close"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Archived Projects'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('archived'),
		'visible' => Yii::app()->user->checkAccess('view_project'),
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
		'id' => 'project-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array('class' => 'LinkColumn', 'name' => 'name'),
			array(
				'name' => 'count_milestones',
				'value' => 'CHtml::link($data->count_milestones, array("milestone/index", "project" => $data->id), array("class" => "btn btn-xs btn-default"))',
				'type' => 'raw',
				'visible' => Yii::app()->user->checkAccess('view_milestone'),
			),
			array(
				'name' => 'count_tasks',
				'value' => 'CHtml::link($data->count_tasks, array("task/index", "project" => $data->id), array("class" => "btn btn-xs btn-default"))',
				'type' => 'raw',
				'visible' => Yii::app()->user->checkAccess('view_task'),
			),
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to archive this project?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_project", array("project" => $data))',
					),
					'delete' => array(
						'label' => '<i class="glyphicon glyphicon-save"></i>',
						'options' => array(
							'class' => 'btn btn-default btn-sm archive',
							'title' => Yii::t('core.crud', 'Archive Project'),
						),
						'url' => 'Yii::app()->controller->createUrl("archive", array("id" => $data->id))',
						'visible' => 'Yii::app()->user->checkAccess("archive_project", array("project" => $data))',
					),
				),
			),
		),
	)); ?>
</div>

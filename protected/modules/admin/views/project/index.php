<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Project');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Project'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_project'),
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
		'id' => 'project-grid',
		'dataProvider' => $provider,
		'columns' => array(
			'name',
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
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this project?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_project", array("project" => $data))',
					),
					'delete' => array(
						'visible' => 'Yii::app()->user->checkAccess("delete_project", array("project" => $data))',
					),
				),
			),
		),
	)); ?>
</div>

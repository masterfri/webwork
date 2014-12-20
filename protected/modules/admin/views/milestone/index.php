<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Milestones');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $project)) ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('admin.crud', 'Milestones'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Milestone'),
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('create_milestone', array('project' => $project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Back to Project'),
			'class' => 'btn btn-default',
		),
		'url' => array('project/view', 'id' => $project->id),
		'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $project)),
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
		'id' => 'milestone-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array('class' => 'LinkColumn', 'name' => 'name'),
			'due_date:date',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this milestone?'),
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

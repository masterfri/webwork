<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Applications');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $project)) ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('core.crud', 'Applications'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Application'),
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('create_application', array('project' => $project)),
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
);

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'application-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array('class' => 'LinkColumn', 'name' => 'name'),
			'description',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this application?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_application", array("application" => $data))',
					),
					'delete' => array(
						'visible' => 'Yii::app()->user->checkAccess("delete_application", array("application" => $data))',
					),
				),
			),
		),
	)); ?>
</div>

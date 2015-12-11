<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Application Entities');
		
$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($application->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $application->project)) ? array('project/view', 'id' => $application->project->id) : false, 
	CHtml::encode($application->name) => Yii::app()->user->checkAccess('view_application', array('application' => $application)) ? array('application/view', 'id' => $application->id) : false, 
	Yii::t('core.crud', 'Application Entities'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-play-circle"></i>',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Build'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('build', 'application' => $application->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $application)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Application Entity'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create', 'application' => $application->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $application)),
	),
);

?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'activity-grid',
		'dataProvider' => $provider,
		'columns' => array(
			'name',
			'label',
			'description',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this application entity?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('design_application', array('application' => $application)) ? ' {update}' : '').
					(Yii::app()->user->checkAccess('design_application', array('application' => $application)) ? ' {delete}' : ''),
				'buttons' => array(
					'update' => array(
						'options' => array(
							'class' => 'btn btn-default btn-sm update',
							'title' => Yii::t('core.crud', 'Update'),
						),
					),
				),
			),
		),
	)); ?>
</div>

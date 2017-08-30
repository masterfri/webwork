<?php

$this->pageHeading = Yii::t('core.crud', 'New From Template');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($application->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $application->project)) ? array('project/view', 'id' => $application->project->id) : false, 
	CHtml::encode($application->name) => Yii::app()->user->checkAccess('view_application', array('application' => $application)) ? array('application/view', 'id' => $application->id) : false, 
	Yii::t('core.crud', 'Application Entities') => Yii::app()->user->checkAccess('design_application', array('application' => $application)) ? array('index', 'application' => $application->id) : false, 
	Yii::t('core.crud', 'Create From Template'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index', 'application' => $application->id),
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
			'label',
			'description',
			array(
				'class' => 'ButtonColumn',
				'template' => '{select}',
				'buttons' => array(
					'select' => array(
						'url' => 'Yii::app()->controller->createUrl("create", array("application" => ' . $application->id . ', "template" => $data->id))',
						'visible' => 'Yii::app()->user->checkAccess("design_application")',
						'label' => '<i class="glyphicon glyphicon-arrow-right"></i>',
						'options' => array(
							'class' => 'btn btn-default btn-sm create',
							'title' => Yii::t('core.crud', 'Create'),
						),
					),
				),
			),
		),
	)); ?>
</div>

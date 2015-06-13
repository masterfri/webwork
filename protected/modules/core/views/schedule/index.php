<?php 

$this->pageHeading = Yii::t('core.crud', 'Schedule');

if (null !== $project) {
	$this->breadcrumbs = array(
		Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
		CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $project)) ? array('project/view', 'id' => $project->id) : false, 
		Yii::t('core.crud', 'Schedule'), 
	);
	$this->menu = array(
		array(
			'label' => '<i class="glyphicon glyphicon-pencil"></i>', 
			'linkOptions' => array(
				'title' => Yii::t('core.crud', 'Scheduling'),
				'class' => 'btn btn-default',
			),
			'url' => array('update', 'project' => $project->id),
			'visible' => Yii::app()->user->checkAccess('update_schedule', array('project' => $project)),
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
} else {
	$this->breadcrumbs = array(
		Yii::t('core.crud', 'Schedule'), 
	);
}

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>	
	<?php $this->widget('ScheduleWidget', array(
		'hr' => $data['hr'],
		'grid' => $data['grid'],
		'start' => $start,
		'noHr' => !$show_all_users,
		'showSpareTime' => $project === null,
		'showProject' => $project === null,
		'dataAction' => 'index',
		'dataGetParams' => $project !== null ? array(
			'project' => $project->id, 
		) : array(),
	)); ?>
</div>

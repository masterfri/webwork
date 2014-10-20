<?php

$this->pageHeading = Yii::t('admin.crud', 'Report Time for: {task}', array(
	'{task}' => CHtml::encode($task->name),
));

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Daily Time Report') => Yii::app()->user->checkAccess('daily_time_report') ? array('daily') : false, 
	Yii::t('admin.crud', 'New Time Report'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Back to Task'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('task/view', 'id' => $task->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $task)),
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
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" data-marker="ajax-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div data-marker="ajax-body">
			<?php $this->renderPartial('_form_report', array(
				'model' => $model,
				'short' => true,
			)); ?>
		</div>
	</div>
</div>

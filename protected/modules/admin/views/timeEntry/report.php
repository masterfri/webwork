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
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ' . Yii::t('admin.crud', 'Back to Task'), 
		'url' => array('task/view', 'id' => $task->id),
		'visible' => Yii::app()->user->checkAccess('view_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('admin.crud', 'Daily Time Report'), 
		'url' => array('daily'),
		'visible' => Yii::app()->user->checkAccess('daily_time_report'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form', array(
			'model' => $model,
			'short' => true,
		)); ?>
	</div>
</div>

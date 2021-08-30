<?php

$this->pageHeading = Yii::t('core.crud', 'New Work');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Work') => Yii::app()->user->checkAccess('update_completion_report') ? array('completionReport/update', 'id' => $model->report_id) : false, 
	Yii::t('core.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Completion Report'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('completionReport/update', 'id' => $model->report_id),
		'visible' => Yii::app()->user->checkAccess('update_completion_report'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" data-marker="ajax-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div data-marker="ajax-body">
			<?php $this->renderPartial('_form', array(
				'model' => $model,
			)); ?>
		</div>
	</div>
</div>

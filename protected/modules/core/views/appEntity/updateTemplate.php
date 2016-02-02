<?php

$this->pageHeading = Yii::t('core.crud', 'Template Updating');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	Yii::t('core.crud', 'Templates') => Yii::app()->user->checkAccess('design_application') ? array('templates') : false, 
	Yii::t('core.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'View Template'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('design_application'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Templates'), 
			'class' => 'btn btn-default',
		),
		'url' => array('templates'),
		'visible' => Yii::app()->user->checkAccess('design_application'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" data-marker="ajax-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div data-marker="ajax-body">
			<?php $this->renderPartial('_tplform', array(
				'model' => $model,
			)); ?>
		</div>
	</div>
</div>

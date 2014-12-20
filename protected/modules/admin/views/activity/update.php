<?php

$this->pageHeading = Yii::t('admin.crud', 'Activity Updating');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Activities') => Yii::app()->user->checkAccess('view_activity') ? array('index') : false, 
	Yii::t('admin.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Activity'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'View Activity'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Activities'), 
			'class' => 'btn btn-default',
		), 
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_activity'),
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

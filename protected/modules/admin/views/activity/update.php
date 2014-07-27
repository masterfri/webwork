<?php

$this->pageHeading = Yii::t('admin.crud', 'Updating Activity');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Activity') => Yii::app()->user->checkAccess('view_activity') ? array('index') : false, 
	Yii::t('admin.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Activity'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ' . Yii::t('admin.crud', 'View Activity'), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Activity'), 
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_activity'),
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
		)); ?>
	</div>
</div>

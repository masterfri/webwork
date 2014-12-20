<?php

$this->pageHeading = Yii::t('admin.crud', 'File Category Updating');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'File Categories') => Yii::app()->user->checkAccess('view_file_category') ? array('index') : false, 
	Yii::t('admin.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create File Category'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_file_category'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'View File Category'),
			'class' => 'btn btn-default',
		),
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_file_category'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage File Categories'),
			'class' => 'btn btn-default',
		),
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_file_category'),
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

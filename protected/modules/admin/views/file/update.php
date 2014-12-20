<?php

$this->pageHeading = Yii::t('admin.crud', 'File Updating');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Files') => Yii::app()->user->checkAccess('view_file') ? array('index') : false, 
	Yii::t('admin.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-upload"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Upload'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_file'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'View File'), 
			'class' => 'btn btn-default',
		),
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_file'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Files'), 
			'class' => 'btn btn-default',
		),
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_file'),
	),
);

?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div class="form-content">
			<?php $this->renderPartial('_form', array(
				'model' => $model,
			)); ?>
		</div>
	</div>
</div>

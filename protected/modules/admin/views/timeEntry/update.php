<?php

$this->pageHeading = Yii::t('admin.crud', 'Time Entry Updating');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Time Entries') => Yii::app()->user->checkAccess('view_time_entry') ? array('index') : false, 
	Yii::t('admin.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Time Entry'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'View Time Entry'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_time_entry', array('entry' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Time Entries'), 
			'class' => 'btn btn-default',
		), 
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_time_entry'),
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

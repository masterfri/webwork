<?php

$this->pageHeading = Yii::t('admin.crud', 'Invoice Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Invoice') => Yii::app()->user->checkAccess('view_invoice') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Invoice'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Invoice'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Invoice'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this invoice?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Invoice'), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_invoice'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'project',
			'comments',
			'payd:boolean',
			'items:array',
			'time_created:datetime',
			'created_by',
 
		),
	)); ?>
</div>

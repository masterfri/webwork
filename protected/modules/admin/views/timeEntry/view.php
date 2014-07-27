<?php

$this->pageHeading = Yii::t('admin.crud', 'Time Entry Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Time Entry') => Yii::app()->user->checkAccess('view_time_entry') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Time Entry'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Time Entry'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Time Entry'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this time entry?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Time Entry'), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_time_entry'),
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
			'task',
			'user',
			'activity',
			'amount',
			'description',
			'date_created:datetime',
			'created_by',
 
		),
	)); ?>
</div>

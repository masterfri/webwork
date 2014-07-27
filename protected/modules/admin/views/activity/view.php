<?php

$this->pageHeading = Yii::t('admin.crud', 'Activity Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Activity') => Yii::app()->user->checkAccess('view_activity') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Activity'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Activity'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Activity'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this activity?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Activity'), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_activity'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo CHtml::encode($model->name); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'description:ntext',
			'time_created:datetime',
			'created_by',
		),
	)); ?>
</div>

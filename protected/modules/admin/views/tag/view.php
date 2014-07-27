<?php

$this->pageHeading = Yii::t('admin.crud', 'Tag Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Tag') => Yii::app()->user->checkAccess('view_tag') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Tag'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_tag'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Tag'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_tag'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Tag'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this tag?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_tag'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Tag'), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_tag'),
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
			'name',
			array('name' => 'color', 'type' => 'raw', 'value' => sprintf('<div style="width: 20px; height: 20px; background: %s; border: 1px solid black; "></div>', $model->color)),
			'time_created:datetime',
			'created_by',
 
		),
	)); ?>
</div>

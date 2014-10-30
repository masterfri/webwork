<?php

$this->pageHeading = Yii::t('admin.crud', 'Activity Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Activity') => Yii::app()->user->checkAccess('view_activity') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Activity'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Activity'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_activity'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>', 
		'linkOptions' => array(
			'class' => 'btn btn-default dropdown-toggle',
			'data-toggle' => 'dropdown',
		),
		'itemOptions' => array(
			'class' => 'dropdown',
		),
		'items' => array(
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
		),
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
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('activity', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

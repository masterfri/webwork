<?php

$this->pageHeading = Yii::t('core.crud', 'Template Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	Yii::t('core.crud', 'Templates') => Yii::app()->user->checkAccess('design_application') ? array('templates') : false, 
	CHtml::encode($model->name)
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Templates'), 
			'class' => 'btn btn-default',
		),
		'url' => array('templates'),
		'visible' => Yii::app()->user->checkAccess('design_application'),
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update'), 
				'url' => array('updateTemplate', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_entity_template', array('template' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('deleteTemplate', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this template?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_entity_template', array('template' => $model)),
			),
		),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo CHtml::encode($model->name); ?></h3>
	</div>
	<div class="panel-body">
		<pre><?php echo CHtml::encode($model->plain_source); ?></pre>
	</div>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('activity', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

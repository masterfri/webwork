<?php

$this->pageHeading = Yii::t('core.crud', 'Time Entry Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Time Entries') => Yii::app()->user->checkAccess('view_time_entry') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Time Entry'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Time Entries'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_time_entry'),
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Time Entry'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_time_entry', array('entry' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Time Entry'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this time entry?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_time_entry', array('entry' => $model)),
			),
		),
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
			array(
				'name' => 'project',
				'type' => 'raw',
				'value' => $model->project && Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ?
					CHtml::link(CHtml::encode($model->project), array('project/view', 'id' => $model->project_id)) :
					$model->project,
			),
			array(
				'name' => 'task',
				'type' => 'raw',
				'value' => $model->task && Yii::app()->user->checkAccess('view_task', array('task' => $model->task)) ?
					CHtml::link(CHtml::encode($model->task), array('task/view', 'id' => $model->task_id)) :
					$model->task,
			),
			'user',
			'activity',
			'amount:hours',
			'description',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('timeEntry', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->date_created); ?>
	</div>
</div>

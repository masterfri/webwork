<?php

$this->pageHeading = Yii::t('admin.crud', 'Invoice Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Invoice') => Yii::app()->user->checkAccess('view_invoice') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Invoice'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Invoice'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_invoice'),
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
				'name' => 'id',
				'value' => $model->getNumber(),
			),
			'from',
			'to',
			array(
				'name' => 'project',
				'type' => 'raw',
				'value' => $model->project && Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ?
					CHtml::link(CHtml::encode($model->project), array('project/view', 'id' => $model->project_id)) :
					$model->project,
			),
			'comments',
			'amount:money',
			'payd:money',
			'draft:boolean',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('tag', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('invoice', 'Items') ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'items-grid',
		'dataProvider' => $model->getItems(),
		'columns' => array(
			array(
				'header' => '#',
				'value' => '$row + 1',
			),
			'name',
			'hours:hours',
			'value:money',
		),
	)); ?>
</div>


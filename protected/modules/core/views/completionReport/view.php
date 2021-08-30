<?php

$this->pageHeading = Yii::t('core.crud', 'Completion Report Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Completion Reports') => Yii::app()->user->checkAccess('view_completion_report') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Completion Report'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_completion_report'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Completion Reports'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_completion_report'),
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
				'label' => '<i class="glyphicon glyphicon-file"></i> ' . Yii::t('core.crud', 'Export to PDF'), 
				'url' => array('pdf', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('view_completion_report', array('report' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Completion Report'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_completion_report'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Completion Report'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this completion report?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_completion_report'),
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
				'name' => 'number',
				'value' => $model->getFormattedNumber(),
			),
			'performer',
			'contragent',
			'contract_number',
			'contract_date:date',
			'date:date',
			'draft:boolean',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('tag', 'Created by'); ?>
		<?php echo CHtml::encode(CHtml::value($model, 'created_by', Yii::t('core.crud', 'System'))); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('completionReport', 'Completed Work') ?></h3>
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
			'qty',
			'price:abstractMoney',
		),
	)); ?>
</div>


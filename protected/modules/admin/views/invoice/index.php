<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Invoice');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Invoice'), 
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
		'label' => '<i class="glyphicon glyphicon-search"></i>', 
		'url' => '#',
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Search'),
			'class' => 'btn btn-default search-button',
			'data-toggle' => 'search-form',
		),
	),
);

?>

<div class="panel panel-default search-form" style="display: none;">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('admin.crud', 'Search'); ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_search',array(
			'model' => $model,
		)); ?>
	</div>
</div>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'invoice-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array(
				'class' => 'LinkColumn',
				'name' => 'id',
				'value' => '$data->getNumber()',
			),
			'from',
			'to',
			array(
				'class' => 'LinkColumn', 
				'name' => 'project',
				'linkExpression' => 'array("project/view", "id" => $data->project_id)',
				'activityExpression' => 'Yii::app()->user->checkAccess("view_project", array("project" => $data->project))',
			),
			'amount:money',
			'payd:money',
			'draft:boolean',
			'time_created:datetime',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this invoice?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_invoice') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_invoice') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>

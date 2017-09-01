<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Invoices');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Invoices'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Invoice'),
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i>', 
		'url' => '#',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Search'),
			'class' => 'btn btn-default search-button',
			'data-toggle' => 'search-form',
		),
	),
);

?>

<div class="panel panel-default search-form" style="display: none;">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Search'); ?></h3>
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
                'rowCssClassExpression' => '$data->getRowCssClass();',
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
                        array(
                            'header' => 'Остаток',
                            'value' => '$data->getRest()',
                            'type' => 'money'
			),
			'draft:boolean',
			'time_created:datetime',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this invoice?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_invoice') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_invoice') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>

<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Payment');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Payment'), 
);

$this->menu = array(
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
		'id' => 'payment-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array(
				'class' => 'LinkColumn',
				'name' => 'id',
				'value' => '$data->getNumber()',
			),
			array(
				'class' => 'LinkColumn',
				'name' => 'invoice',
				'linkExpression' => 'Yii::app()->controller->createUrl("invoice/view", array("id" => $data->invoice_id))',
				'activityExpression' => 'Yii::app()->user->checkAccess("view_invoice", array("invoice" => $data->invoice))',
			),
			'invoice.from',
			'invoice.to',
			array(
				'name' => 'type',
				'value' => '$data->getType()',
			),
			'amount:money',
			'date_created:datetime',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this payment?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_payment') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_payment') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>

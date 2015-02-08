<?php

$this->pageHeading = Yii::t('core.crud', 'Invoice Updating');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Invoices') => Yii::app()->user->checkAccess('view_invoice') ? array('index') : false, 
	Yii::t('core.crud', 'Update'),
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
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'View Invoice'),
			'class' => 'btn btn-default',
		),
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_invoice'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Invoices'),
			'class' => 'btn btn-default',
		),
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_invoice'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form', array(
			'model' => $model,
		)); ?>
	</div>
</div>

<div class="pull-right">
	<?php $this->widget('zii.widgets.CMenu', array(
		'items' => array(
			array(
				'label' => '<i class="glyphicon glyphicon-plus"></i>', 
				'linkOptions' => array(
					'title' => Yii::t('core.crud', 'Add Invoice Item'),
					'class' => 'btn btn-default',
					'data-raise' => 'ajax-modal',
				),
				'url' => array('invoiceItem/create', 'invoice' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_invoice'),
			),
		),
		'encodeLabel' => false,
		'activateItems' => true,
		'htmlOptions' => array(
			'class' => 'nav nav-pills context-menu',
		),
		'submenuHtmlOptions' => array(
			'class' => 'dropdown-menu dropdown-menu-right pull-right',
			'role' => 'menu',
		),
	)); ?>
</div>
<div class="clearfix"></div>

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
			array(
				'class' => 'ButtonColumn',
				'updateButtonUrl' => 'Yii::app()->controller->createUrl("invoiceItem/update", array("id" => $data->id))',
				'deleteButtonUrl' => 'Yii::app()->controller->createUrl("invoiceItem/delete", array("id" => $data->id))',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this invoice item?'),
				'template' => '{update} {delete}',
				'buttons' => array(
					'update' => array(
						'options' => array(
							'data-raise' => 'ajax-modal',
							'class' => 'btn btn-default btn-sm update',
							'title' => Yii::t('core.crud', 'Update'),
						),
					),
				),
			),
		),
	)); ?>
</div>

<?php

Yii::app()->clientScript->registerScript('ajax', "
$.ajaxBindings.on('invoiceitem.created invoiceitem.updated', function() {
	$.fn.yiiGridView.update('items-grid');
});
");


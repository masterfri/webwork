<?php

$this->pageHeading = Yii::t('admin.crud', 'Payment Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Payment') => Yii::app()->user->checkAccess('view_payment') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Payment'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_payment'),
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Payment'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_payment'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Payment'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this payment?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_payment'),
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
				'name' => 'invoice',
				'type' => 'raw',
				'value' => Yii::app()->user->checkAccess('view_invoice', array('invoice' => $model->invoice)) ?
					CHtml::link(CHtml::encode($model->invoice), array('invoice/view', 'id' => $model->invoice_id)) :
					$model->invoice,
			),
			'invoice.from',
			'invoice.to',
			array(
				'name' => 'type',
				'value' => $model->getType(),
			),
			'amount:money',
			'description',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('tag', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->date_created); ?>
	</div>
</div>

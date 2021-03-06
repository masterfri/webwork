<?php

$this->pageHeading = Yii::t('core.crud', 'New Payment');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Payments') => Yii::app()->user->checkAccess('view_payment') ? array('index') : false, 
	Yii::t('core.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Invoice'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('invoice/view', 'id' => $invoice->id),
		'visible' => Yii::app()->user->checkAccess('view_invoice', array('invoice' => $invoice)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Payments'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_payment'),
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

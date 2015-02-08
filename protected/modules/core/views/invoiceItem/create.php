<?php

$this->pageHeading = Yii::t('core.crud', 'New Invoice Item');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Invoice Items') => Yii::app()->user->checkAccess('update_invoice') ? array('invoice/update', 'id' => $model->invoice_id) : false, 
	Yii::t('core.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Invoice'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('invoice/update', 'id' => $model->invoice_id),
		'visible' => Yii::app()->user->checkAccess('update_invoice'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" data-marker="ajax-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div data-marker="ajax-body">
			<?php $this->renderPartial('_form', array(
				'model' => $model,
			)); ?>
		</div>
	</div>
</div>

<?php

$this->pageHeading = Yii::t('admin.crud', 'New Payment');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Payment') => Yii::app()->user->checkAccess('view_payment') ? array('index') : false, 
	Yii::t('admin.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Payment'), 
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

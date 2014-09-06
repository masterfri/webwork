<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Payment');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Payment'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Payment'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_payment'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i> ' . Yii::t('admin.crud', 'Search'), 
		'url' => '#',
		'linkOptions' => array(
			'class' => 'search-button',
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
			'project',
			'task',
			'user',
			'type',
			'amount',
			'description',
			'date_created:datetime',
			'created_by',
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

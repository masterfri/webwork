<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Activities');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Activities'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Activity'), 
			'class' => 'btn btn-default',
			'data-raise' => 'ajax-modal',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_activity'),
	),
);

?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'activity-grid',
		'dataProvider' => $provider,
		'columns' => array(
			'name',
			'description',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this activity?'),
				'template' => 
					(Yii::app()->user->checkAccess('update_activity') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_activity') ? ' {delete}' : ''),
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
$.ajaxBindings.on('activity.created activity.updated', function() {
	$.fn.yiiGridView.update('activity-grid');
});
");

<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Activity');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Activity'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>',
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Activity'), 
			'class' => 'btn btn-default',
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
			array('class' => 'LinkColumn', 'name' => 'name'),
			'description',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this activity?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_activity') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_activity') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>

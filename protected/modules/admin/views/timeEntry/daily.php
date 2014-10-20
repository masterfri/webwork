<?php

$this->pageHeading = Yii::t('admin.crud', 'Daily Time Report');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Time Entry') => Yii::app()->user->checkAccess('view_time_entry') ? array('index') : false, 
	Yii::t('admin.crud', 'Daily Time Report'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Time Entry'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_time_entry'),
	),
);

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'timeentry-grid',
		'template' => '{items} <div class="table-totals">' . Yii::t('admin.crud', 'Total') . ': <span>' . $sum . '</span></div> {pager}',
		'dataProvider' => $provider,
		'columns' => array(
			'project',
			'task',
			'activity',
			'amount',
			'date_created:time',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this time entry?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_time_entry", array("entry" => $data))',
					),
					'delete' => array(
						'visible' => 'Yii::app()->user->checkAccess("delete_time_entry", array("entry" => $data))',
					),
				),
			),
		),
	)); ?>
</div>

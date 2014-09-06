<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Time Entry');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Time Entry'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Time Entry'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_time_entry'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('admin.crud', 'Daily Time Report'), 
		'url' => array('daily'),
		'visible' => Yii::app()->user->checkAccess('daily_time_report'),
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
		'id' => 'timeentry-grid',
		'template' => '{items} <div class="table-totals">' . Yii::t('admin.crud', 'Total') . ': <span>' . $sum . '</span></div> {pager}',
		'dataProvider' => $provider,
		'columns' => array(
			'project',
			'task',
			'user',
			'activity',
			'amount',
			'date_created:datetime',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this time entry?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_time_entry') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_time_entry') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>

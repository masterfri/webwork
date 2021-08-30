<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Completion Reports');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Completion Reports'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Completion Report'),
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_completion_report'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i>', 
		'url' => '#',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Search'),
			'class' => 'btn btn-default search-button',
			'data-toggle' => 'search-form',
		),
	),
);

?>

<div class="panel panel-default search-form" style="display: none;">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Search'); ?></h3>
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
		'id' => 'completion-report-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array(
				'class' => 'LinkColumn', 
				'name' => 'number',
				'value' => '$data->getFormattedNumber()',
				'linkExpression' => 'array("view", "id" => $data->id)',
			),
			'contract_number',
			'contract_date:date',
			'performer',
			'contragent',
			'draft:boolean',
			'time_created:datetime',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this completion report?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_completion_report') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_completion_report') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>

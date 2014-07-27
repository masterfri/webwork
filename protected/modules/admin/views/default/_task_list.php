<?php $this->widget('GridView', array(
	'id' => 'task-grid',
	'dataProvider' => $provider,
	'columns' => array(
		'name',
		array(
			'name' => 'priority',
			'value' => '$data->getPriority()',
		),
		'date_sheduled:date',
		'due_date:date',
		array(
			'class' => 'ButtonColumn',
			'template' => '{view}',
			'viewButtonUrl' => 'Yii::app()->createUrl("/admin/task/view", array("id" => $data->id))',
		),
	),
)); ?>

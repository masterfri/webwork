<?php $this->widget('ScheduleWidget', array(
	'id' => 'scheduling-grid',
	'htmlOptions' => array(
		'data-onload' => 'schedulegrid.init', 
		'class' => 'schedule-table editable',
	),
	'hr' => $data['hr'],
	'grid' => $data['grid'],
	'start' => $start,
	'showSpareTime' => true,
	'showProject' => true,
	'editMode' => true,
	'dataAction' => 'update',
	'dataGetParams' => array(
		'project' => $project->id, 
	),
)); ?>

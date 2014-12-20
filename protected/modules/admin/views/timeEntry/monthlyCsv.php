<?php

$this->enableWebLog(false);
$provider->pagination = false;

$this->widget('CsvRenderer', array(
	'filename' => 'monthly-report.csv',
	'showHeads' => true,
	'provider' => $provider,
	'attributes' => array(
		'project',
		'task',
		'user',
		'activity',
		'description',
		'amount',
		'date_created:datetime',
	),
)); 

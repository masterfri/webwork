<?php

$flashes = Yii::app()->user->getFlashes();
if(! empty($flashes)) {
	$code = '';
	foreach ($flashes as $key => $val) {
		if ('counters' == $key) continue;
		if (strpos($key, 'error') !== false) {
			$type = 'error';
			$title = Yii::t('core.crud', 'Error');
		} elseif (strpos($key, 'success') !== false) {
			$type = 'success';
			$title = Yii::t('core.crud', 'Success');
		} elseif (strpos($key, 'warning') !== false) {
			$type = 'warning';
			$title = Yii::t('core.crud', 'Warning');
		} else {
			$type = 'default';
			$title = Yii::t('core.crud', 'Success');
		}
		$code .= "$.ajaxBindings.message(" . CJSON::encode(array(
			'title' => $title,
			'type' => $type,
			'text' => $val,
		)) . ");\n";
	}
	Yii::app()->clientScript->registerScript('flash-messages', $code);
}

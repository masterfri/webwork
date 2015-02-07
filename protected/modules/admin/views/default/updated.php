<?php $this->renderPartial('_task_list', array(
	'model' => $model,
	'provider' => $provider,
	'group_date' => false,
	'mark_read_button' => true,
)); 

Yii::app()->clientScript->registerScript('markseen', "
$.ajaxBindings.on('tasks.markseen', function() {
	$.fn.yiiListView.update('task-grid');
	$('#dashboard-tabs li.active .badge').remove();
});
window.notificationCallback = function(data) {
	data.total -= data.task.length;
	data.task = [];
	$.fn.yiiListView.update('task-grid');
	return data;
}
");

?>

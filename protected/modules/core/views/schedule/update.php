<?php 

$this->pageHeading = Yii::t('core.crud', 'Scheduling');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $project)) ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('core.crud', 'Schedule') => Yii::app()->user->checkAccess('view_schedule', array('project' => $project)) ? array('index', 'project' => $project->id) : false,
	Yii::t('core.crud', 'Scheduling')
);
$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-th"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Schedule'),
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('view_schedule', array('project' => $project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Project'),
			'class' => 'btn btn-default',
		),
		'url' => array('project/view', 'id' => $project->id),
		'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $project)),
	),
);

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="schedule-container">
		<div class="row">
			<div class="col-sm-10 no-gutter-right">
				<div class="schedule-wrapper-grid">
					<?php $this->renderPartial('_grid', array(
						'data' => $data,
						'start' => $start,
						'project' => $project,
					)); ?>
				</div>
			</div>
			<div class="col-sm-2 no-gutter-left">
				<div class="schedule-wrapper-tasks">
					<?php $this->renderPartial('_tasklist', array(
						'tasks' => $tasks,
						'project' => $project,
					)); ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

$notSetTilte = Yii::t('core.crud', 'Not set');
$priorityLabels = CJSON::encode(ViewHelper::allPriorityLabels());
$putUrl = CJSON::encode($this->createUrl('put'));
$resetUrl = CJSON::encode($this->createUrl('reset'));
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery.ui');
$cs->registerCoreScript('bbq');
$cs->registerScript('scheduling', 
<<<EOS

function putTask(day, task) {
	var url = $.param.querystring($putUrl, {
		'task': task.draggable.data('task'), 
		'date': day.data('date'), 
		'user': day.data('user')
	});
	task.draggable.hide();
	task.helper.remove();
	$('#scheduling-grid').ajaxRequest(url);
}

function resetTask(task) {
	var url = $.param.querystring($resetUrl, {
		'task': task.draggable.data('task'), 
	});
	task.draggable.hide();
	task.helper.remove();
	$('#scheduling-grid').ajaxRequest(url);
}

$.ajaxBindings.on('tasklist.init', function() {
	$('#tasklist > ul > li.task').draggable({
		revert: true
	});
	$('#tasklist > ul').droppable({
		tolerance: 'pointer',
		hoverClass: 'hover',
		activeClass: 'active',
		accept: '.t',
		drop: function(e, ui) {
			resetTask(ui);
		}
	});
});

$.ajaxBindings.on('schedulegrid.init', function() {
	$('#scheduling-grid td:not(.full,.past,.day-off)').droppable({
		tolerance: 'pointer',
		hoverClass: 'hover',
		drop: function(e, ui) {
			putTask($(this), ui);
		}
	});
	$('#scheduling-grid li.t:not(.noupdate)').draggable({
		revert: true,
		appendTo: 'body',
		helper: function(e) {
			var h = $('<li class="task schedule-draggable-helper"></li>');
			var elem = $(e.currentTarget);
			var priorityLabels = $priorityLabels;
			var data = elem.data();
			h.width(elem.width());
			h.attr('data-task', data.task);
			h.append(
				$('<div class="title"></div>').append(elem.find('.task-name a').clone())
			);
			h.append(
				$('<div class="task-details"></div>').append(
					$('<div class="priority">').append(
						'<span class="glyphicon glyphicon-arrow-up"></span>'
					).append(
						data.taskPriority in priorityLabels ? priorityLabels[data.taskPriority] : '<span class="not-set">$notSetTilte</span>'
					)
				).append(
					$('<div class="due-date"></div>').append(
						'<span class="glyphicon glyphicon-fire"></span>'
					).append(
						data.taskDueDate ? data.taskDueDate : '<span class="not-set">$notSetTilte</span>'
					)
				).append(
					$('<div class="estimate"></div>').append(
						'<span class="glyphicon glyphicon-time"></span>'
					).append(
						data.taskEstimate ? data.taskEstimate : '<span class="not-set">$notSetTilte</span>'
					)
				)
			);
			return h;
		},
		start: function(e, ui) {
			var elem = $(e.currentTarget);
			var task = elem.data('task');
			$('#scheduling-grid li[data-task=' + task + ']').each(function() {
				$(this).addClass('tmp-hide')
					.closest('td')
					.addClass('tmp-vacant')
					.droppable({
						tolerance: 'pointer',
						hoverClass: 'hover',
						drop: function(e, ui) {
							putTask($(this), ui);
						}
					});
			});
		},
		stop: function(e, ui) {
			$('#scheduling-grid .tmp-hide').each(function() {
				$(this).removeClass('tmp-hide')
					.closest('td')
					.removeClass('tmp-vacant')
					.droppable('destroy');
			});
		}
	});
});

$('#tasklist').trigger('tasklist.init');
$('#scheduling-grid').trigger('schedulegrid.init');

EOS
);

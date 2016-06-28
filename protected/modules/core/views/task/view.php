<?php

$this->pageHeading = Yii::t('core.crud', 'Task Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('core.crud', 'Tasks') => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Watch'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('watch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $model)) && $model->user_subscription === null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-close"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Unwatch'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('unwatch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $model)) && $model->user_subscription !== null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Task'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_task', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Tasks'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>', 
		'linkOptions' => array(
			'class' => 'btn btn-default dropdown-toggle',
			'data-toggle' => 'dropdown',
		),
		'itemOptions' => array(
			'class' => 'dropdown',
		),
		'items' => array(
			array(
				'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('core.crud', 'Report Time'), 
				'linkOptions' => array(
					'data-raise' => 'ajax-modal',
				),
				'url' => array('timeEntry/report', 'task' => $model->id),
				'visible' => Yii::app()->user->checkAccess('report_time_entry', array('task' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Task'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_task', array('task' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('core.crud', 'Estimate Task'), 
				'linkOptions' => array(
					'data-raise' => 'ajax-modal',
				),
				'url' => array('estimate', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('estimate_task', array('task' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Task'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this task?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_task', array('task' => $model)),
			),
		),
	),
);

?>
<div class="panel panel-default single-task">
	<div class="panel-heading">
		<h3 class="panel-title">
			<?php echo CHtml::encode($model->name); ?>
			<?php if($model->milestone && Yii::app()->user->checkAccess('view_milestone', array('milestone' => $model->milestone))): ?>
				:: 
				<?php echo CHtml::link(CHtml::encode($model->milestone->name), array('milestone/view', 'id' => $model->milestone->id)); ?>
			<?php endif; ?>
			<?php if(Yii::app()->user->checkAccess('view_project', array('project' => $model->project))): ?>
				:: 
				<?php echo CHtml::link(CHtml::encode($model->project->name), array('project/view', 'id' => $model->project->id)); ?>
			<?php endif; ?>
		</h3>
	</div>
	<div class="task-details">
		<?php $this->widget('DetailView', array(
			'id' => 'task-details',
			'htmlOptions' => array(
				'data-update-on' => 'task.updated',
				'class' => 'table table-striped table-bordered table-condensed detailed-view',
			),
			'data' => $model,		
			'attributes' => array(
				'complexity:number',
				array(
					'name' => 'estimate',
					'value' => ViewHelper::formatEstimate($model->getEstimateRange()),
					'type' => 'raw',
				),
				'timeSpent:hours',
				array(
					'name' => 'regression_risk',
					'value' => $model->getRegressionRisk(),
				),
				'date_sheduled:date',
				'due_date:date',
			),
		)); ?>
		<div class="panel-body">
			<?php if ('' != $model->description): ?>
				<?php 
					$this->beginWidget('MarkdownWidget'); 
					echo $model->description;
					$this->endWidget(); 
				?>
			<?php else: ?>
				<p class="not-set"><?php echo Yii::t('core.crud', 'No description given'); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<div class="task-controls">
		<div class="panel-body">
			<ul class="unstyled">
				<li class="task-phase">
					<?php echo ViewHelper::taskPhaseIcon($model->phase); ?>
					<?php echo CHtml::encode($model->getPhase()); ?>
				</li>
				<?php if (Yii::app()->user->checkAccess('update_task_tags', array('task' => $model))): ?>
					<li class="tags-control hr">
						<?php $this->widget('DropdownMenuSelect', array(
							'name' => 'tags',
							'value' => array_map(function($t) { return $t->id; }, $model->tags),
							'options' => ViewHelper::listTags($model->project->getTags(), array(
								'parentTag' => false, 
								'glue' => false,
								'itemTag' => 'span',
							)),
							'labels' => ViewHelper::listTags($model->project->getTags(), array(
								'parentTag' => false, 
								'glue' => false,
							)),
							'emptyLabel' => '<span class="not-set">' . Yii::t('core.crud', 'Not set') . '</span>',
							'buttonHtmlOptions' => array('class' => 'btn btn-default btn-xs'),
							'button' => '<span class="glyphicon glyphicon-tag"></span> ' . Yii::t('core.crud', 'Change tags'),
							'multiple' => true,
							'doneBtnText' => Yii::t('core.crud', 'Update'),
							'doneBtnHtmlOptions' => array('class' => 'btn btn-default btn-xs'),
							'dropdownHtmlOptions' => array('class' => 'tags-menu'),
							'htmlOptions' => array('class' => 'tags', 'container' => 'ul'),
							'htmlEncodeOptions' => false,
							'multipleLabelSeparator' => '',
						)); ?>
					</li>
				<?php else: ?>
					<li class="tags-view hr">
						<?php echo ViewHelper::listTags($model->tags, array('class' => 'tags')); ?>
					</li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('update_task_priority', array('task' => $model))): ?>
					<li class="priority-control hr">
						<?php $this->widget('DropdownMenuSelect', array(
							'name' => 'priority',
							'value' => $model->priority,
							'options' => Task::getListPriorities(),
							'labels' => ViewHelper::allPriorityLabels(),
							'emptyLabel' => '<span class="not-set">' . Yii::t('core.crud', 'Not set') . '</span>',
							'buttonHtmlOptions' => array(
								'class' => 'btn btn-default btn-xs btn-square',
								'title' => Yii::t('core.crud', 'Change priority'),
							),
							'button' => '<span class="glyphicon glyphicon-arrow-up"></span>',
						)); ?>
					</li>
				<?php else: ?>
					<li class="priority-view hr">
						<span class="glyphicon glyphicon-arrow-up"  title="<?php echo Yii::t('task', 'Priority'); ?>"></span>
						<?php echo ViewHelper::taskPriorityLabel($model->priority); ?>
					</li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('update_task_assignment', array('task' => $model))): ?>
					<li class="assignment-control">
						<?php $this->widget('DropdownMenuSelect', array(
							'name' => 'assignment',
							'value' => $model->assigned_id,
							'options' => $model->project->getTeamList(),
							'emptyLabel' => '<span class="not-set">' . Yii::t('core.crud', 'Nobody') . '</span>',
							'emptyOption' => Yii::t('core.crud', 'Nobody'),
							'buttonHtmlOptions' => array(
								'class' => 'btn btn-default btn-xs btn-square',
								'title' => Yii::t('core.crud', 'Change assignment'),
							),
							'button' => '<span class="glyphicon glyphicon-user"></span>',
						)); ?>
					</li>
				<?php else: ?>
					<li class="assignment-view">
						<span class="glyphicon glyphicon-user" title="<?php echo Yii::t('task', 'Assigned'); ?>"></span>
						<?php echo CHtml::encode($model->assigned); ?>
					</li>
				<?php endif; ?>
				<?php if (Yii::app()->user->checkAccess('report_time_entry', array('task' => $model))): ?>
					<li class="timer hr">
						<div id="timer_form_container" class="timer-form-container">
							<form id="timer_form" action="<?php echo $this->createUrl('timeEntry/report'); ?>" method="get" data-raise="ajax-modal">
								<button type="submit" class="btn btn-default btn-xs btn-square" title="<?php echo Yii::t('core.crud', 'Report Time'); ?>">
									<span class="glyphicon glyphicon-plus"></span>
								</button>
								<a id="start_timer" class="btn btn-default btn-xs btn-square" href="#" title="<?php echo Yii::t('core.crud', 'Start'); ?>">
									<span class="glyphicon glyphicon-play"></span>
								</a>
								<a id="stop_timer" class="btn btn-default btn-xs btn-square hidden" href="#" title="<?php echo Yii::t('core.crud', 'Stop'); ?>">
									<span class="glyphicon glyphicon-stop"></span>
								</a>
								<a id="reset_timer" class="btn btn-default btn-xs btn-square" href="#" title="<?php echo Yii::t('core.crud', 'Reset'); ?>">
									<span class="glyphicon glyphicon-remove"></span>
								</a>
								<span id="timer_display">
									<code>0:00:00</code>
									<input type="hidden" name="sec" value="0" />
								</span>
								<input type="hidden" name="task" value="<?php echo $model->id; ?>" />
							</form>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
	<?php if (count($model->attachments)): ?>
		<div class="panel-footer attachments">
			<?php foreach ($model->attachments as $attachment): ?>
				<a class="thumbnail" target="_blank" href="<?php echo $attachment->getUrl(); ?>">
					<?php if ($attachment->getIsImage()): ?>
						<?php echo CHtml::image($attachment->getUrlResized(150, 100), '', array('title' => $attachment->title)); ?>
					<?php else: ?>
						<span class="no-thumb">
							<span class="file-name">
								<?php echo CHtml::encode($attachment->title); ?>
							</span>
							<span class="file-type">
								<?php echo CHtml::encode($attachment->mime); ?>
							</span>
							<span class="file-size">
								<?php echo $attachment->getFriendlySize(); ?>
							</span>
						</span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('task', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

<h3><?php echo Yii::t('core.crud', 'Disscussion'); ?> <a class="small" href="#continue-discussion"><?php echo Yii::t('core.crud', 'Continue'); ?></a></h3>
<div id="comments-list">
	<?php $this->renderPartial('_comments', array(
		'task' => $model,
		'comments' => $model->comments,
		'last_visit' => $last_visit,
	)); ?>
</div>

<?php

$this->renderPartial('_comment_form', array(
	'task' => $model,
	'comment' => $comment,
)); 
$taskId = $model->id;
$changePriorityUrl = CJSON::encode($this->createUrl('changePriority', array('id' => $model->id)));
$changeAssignmentUrl = CJSON::encode($this->createUrl('changeAssignment', array('id' => $model->id)));
$changeTagsUrl = CJSON::encode($this->createUrl('changeTags', array('id' => $model->id)));
$confirmNavigation = CJSON::encode(Yii::t('core.crud', 'Your timer is not saved. Do you want to leave page?'));
$confirmReset = CJSON::encode(Yii::t('core.crud', 'Are you sure you want to reset timer?'));
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('bbq');
$cs->registerScript('task', 
<<<EOS
$('#priority').on('selectitem', function(e, v, l) {
	var url = $.param.querystring($changePriorityUrl, {priority: v, ajax: 1});
	$.post(url);
});
$('#assignment').on('selectitem', function(e, v, l) {
	var url = $.param.querystring($changeAssignmentUrl, {user: v, ajax: 1});
	$.post(url, {}, function() {
		$('#task-details').ajaxUpdate();
	});
});
$('#tags').on('selectitem', function(e, v, l) {
	var url = $.param.querystring($changeTagsUrl, {ajax: 1});
	$.post(url, {tags: v});
});
var timer, timer_value = 0;
$('#start_timer').click(function () {
	$(this).addClass('hidden');
	$('#stop_timer').removeClass('hidden');
	timer = setInterval(function() {
		timer_value++;
		var s = timer_value % 60;
		var m = parseInt(timer_value / 60) % 60;
		var h = parseInt(timer_value / 3600);
		$('#timer_display code').text(h + ':' + (m > 9 ? '' : '0') + m + ':' + (s > 9 ? '' : '0') + s);
		$('#timer_display input').val(timer_value);
	}, 1000);
	window.onbeforeunload = function() {
		return $confirmNavigation;
	};
	return false;
});
$('#stop_timer').click(function () {
	$(this).addClass('hidden');
	$('#start_timer').removeClass('hidden');
	clearInterval(timer);
	return false;
});
$('#reset_timer').click(function () {
	if (confirm($confirmReset)) {
		$('#start_timer').removeClass('hidden');
		$('#stop_timer').addClass('hidden');
		clearInterval(timer);
		window.onbeforeunload = null;
		timer_value = 0;
		$('#timer_display code').text('0:00:00');
		$('#timer_display input').val('0');
	}
	return false;
});
$('#timer_form').submit(function () {
	$('#start_timer').removeClass('hidden');
	$('#stop_timer').addClass('hidden');
	clearInterval(timer);
});
$(document.body).bind('timeentry.created', function() {
	window.onbeforeunload = null;
	timer_value = 0;
	$('#timer_display code').text('0:00:00');
	$('#timer_display input').val('0');
});
$(document.body).on('mousedown', '#comment-form button[type=submit]', function() {
	$('#action_type').val($(this).attr('value'));
});
window.notificationCallback = function(data) {
	var i = data.task.indexOf('$taskId');
	if (i != -1) {
		data.task.splice(i, 1);
		data.total--;
		$('#comments-list').ajaxUpdate({
			ondone: function() {
				setTimeout(function() {
					location.href = '#continue-discussion';
				}, 100);
			}
		});
	}
	return data;
}
$(window).on('scroll', function() {
	var f = $('#timer_form_container');
	if (f.offset().top < $(document.body).scrollTop()) {
		f.addClass('floating');
	} else {
		f.removeClass('floating');
	}
});
EOS
);

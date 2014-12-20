<?php

$this->pageHeading = Yii::t('admin.crud', 'Task Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Tasks') => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Watch'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('watch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $model)) && $model->user_subscription === null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-close"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Unwatch'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('unwatch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $model)) && $model->user_subscription !== null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Task'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_task', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Tasks'), 
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
				'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('admin.crud', 'Report Time'), 
				'linkOptions' => array(
					'data-raise' => 'ajax-modal',
				),
				'url' => array('timeEntry/report', 'task' => $model->id),
				'visible' => Yii::app()->user->checkAccess('report_time_entry', array('task' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Task'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_task', array('task' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('admin.crud', 'Estimate Task'), 
				'linkOptions' => array(
					'data-raise' => 'ajax-modal',
				),
				'url' => array('estimate', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('estimate_task', array('task' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Task'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this task?'),
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
					$this->beginWidget('CMarkdown'); 
					echo $model->description;
					$this->endWidget(); 
				?>
			<?php else: ?>
				<p class="not-set"><?php echo Yii::t('admin.crud', 'No description given'); ?></p>
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
							'emptyLabel' => '<span class="not-set">' . Yii::t('admin.crud', 'Not set') . '</span>',
							'buttonHtmlOptions' => array('class' => 'btn btn-default btn-xs'),
							'button' => '<span class="glyphicon glyphicon-tag"></span> ' . Yii::t('admin.crud', 'Change tags'),
							'multiple' => true,
							'doneBtnText' => Yii::t('admin.crud', 'Update'),
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
							'emptyLabel' => '<span class="not-set">' . Yii::t('admin.crud', 'Not set') . '</span>',
							'buttonHtmlOptions' => array(
								'class' => 'btn btn-default btn-xs btn-square',
								'title' => Yii::t('admin.crud', 'Change priority'),
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
							'emptyLabel' => '<span class="not-set">' . Yii::t('admin.crud', 'Nobody') . '</span>',
							'emptyOption' => Yii::t('admin.crud', 'Nobody'),
							'buttonHtmlOptions' => array(
								'class' => 'btn btn-default btn-xs btn-square',
								'title' => Yii::t('admin.crud', 'Change assignment'),
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
						<form id="timer_form" action="<?php echo $this->createUrl('timeEntry/report'); ?>" method="get" data-raise="ajax-modal">
							<button type="submit" class="btn btn-default btn-xs btn-square" title="<?php echo Yii::t('admin.crud', 'Report Time'); ?>">
								<span class="glyphicon glyphicon-plus"></span>
							</button>
							<a id="start_timer" class="btn btn-default btn-xs btn-square" href="#" title="<?php echo Yii::t('admin.crud', 'Start'); ?>">
								<span class="glyphicon glyphicon-play"></span>
							</a>
							<a id="stop_timer" class="btn btn-default btn-xs btn-square hidden" href="#" title="<?php echo Yii::t('admin.crud', 'Stop'); ?>">
								<span class="glyphicon glyphicon-stop"></span>
							</a>
							<a id="reset_timer" class="btn btn-default btn-xs btn-square" href="#" title="<?php echo Yii::t('admin.crud', 'Reset'); ?>">
								<span class="glyphicon glyphicon-remove"></span>
							</a>
							<span id="timer_display">
								<code>0:00:00</code>
								<input type="hidden" name="sec" value="0" />
							</span>
							<input type="hidden" name="task" value="<?php echo $model->id; ?>" />
						</form>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('task', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

<h3><?php echo Yii::t('admin.crud', 'Disscussion'); ?></h3>
<div id="comments-list">
	<?php $this->renderPartial('_comments', array(
		'task' => $model,
		'comments' => $model->comments,
	)); ?>
</div>

<?php

$this->renderPartial('_comment_form', array(
	'task' => $model,
	'comment' => $comment,
)); 

$changePriorityUrl = CJSON::encode($this->createUrl('changePriority', array('id' => $model->id)));
$changeAssignmentUrl = CJSON::encode($this->createUrl('changeAssignment', array('id' => $model->id)));
$changeTagsUrl = CJSON::encode($this->createUrl('changeTags', array('id' => $model->id)));
$confirmNavigation = CJSON::encode(Yii::t('admin.crud', 'Your timer is not saved. Do you want to leave page?'));
$confirmReset = CJSON::encode(Yii::t('admin.crud', 'Are you sure you want to reset timer?'));
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
	$.post(url);
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
EOS
);

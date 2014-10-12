<?php

$this->pageHeading = Yii::t('admin.crud', 'Task Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Task') => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ' . Yii::t('admin.crud', 'Watch'), 
		'url' => array('watch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $model)) && $model->user_subscription === null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-close"></i> ' . Yii::t('admin.crud', 'Unwatch'), 
		'url' => array('unwatch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $model)) && $model->user_subscription !== null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('admin.crud', 'Report Time'), 
		'url' => array('timeEntry/report', 'task' => $model->id),
		'visible' => Yii::app()->user->checkAccess('report_time_entry', array('task' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Task'), 
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_task', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Task'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_task', array('task' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-time"></i> ' . Yii::t('admin.crud', 'Estimate Task'), 
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
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Task'), 
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)),
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
			'data' => $model,		
			'attributes' => array(
				'complexity',
				'estimate',
				array(
					'name' => 'regression_risk',
					'value' => $model->getRegressionRisk(),
				),
				'date_sheduled:date',
				'due_date:date',
				'time_created:datetime',
				'created_by',
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
							'options' => ViewHelper::listTags($model->project->getAvailableTags(), array(
								'parentTag' => false, 
								'glue' => false,
								'itemTag' => 'span',
							)),
							'labels' => ViewHelper::listTags($model->project->getAvailableTags(), array(
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
						<form id="timer_form" action="<?php echo $this->createUrl('timeEntry/report'); ?>" method="get">
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
</div>

<h3><?php echo Yii::t('admin.crud', 'Disscussion'); ?></h3>
<?php $this->renderPartial('_comments', array(
	'task' => $model,
)); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('admin.crud', 'Add Comment / Action'); ?></h3>
	</div>
	<div class="panel-body">
		<div class="form-content">
	
			<?php $form=$this->beginWidget('ActiveForm', array(
				'id' => 'comment-form',
				'htmlOptions' => array(
					'class'=>'form',
				),
				'enableClientValidation' => true,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'afterValidate' => 'js:function(f,d,e) {
						if (e) $("html, body").animate({scrollTop: $("#comment-form").offset().top - 50}, 1000);
						return true;
					}',
				),
			)); ?>
			
			<?php echo $form->errorSummary($comment, null, null, array('class' => 'alert alert-danger')); ?>

			<div class="form-group">
				<?php echo $form->labelEx($comment, 'content', array('class'=>'control-label')); ?>
				<?php echo $form->textArea($comment, 'content', array(
					'class' => 'form-control',
				)); ?> 
				<?php echo $form->error($comment, 'content', array('class'=>'help-inline')); ?>
			</div>
			
			<div class="form-group">
				<?php if(Yii::app()->user->checkAccess('comment_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-primary',
						'name' => 'action',
						'value' => Task::ACTION_COMMENT,
					), Yii::t('admin.crud', 'Submit')); ?>
				
				<?php if(Yii::app()->user->checkAccess('start_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_START_WORK,
					), Yii::t('admin.crud', 'Start work')); ?>
					
				<?php if(Yii::app()->user->checkAccess('complete_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_COMPLETE_WORK,
					), Yii::t('admin.crud', 'Complete work')); ?>
					
				<?php if(Yii::app()->user->checkAccess('return_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_RETURN,
					), Yii::t('admin.crud', 'Return')); ?>
					
				<?php if(Yii::app()->user->checkAccess('close_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-warning',
						'name' => 'action',
						'value' => Task::ACTION_CLOSE,
					), Yii::t('admin.crud', 'Close')); ?>
					
				<?php if(Yii::app()->user->checkAccess('hold_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-warning',
						'name' => 'action',
						'value' => Task::ACTION_PUT_ON_HOLD,
					), Yii::t('admin.crud', 'Put on-hold')); ?>
					
				<?php if(Yii::app()->user->checkAccess('reopen_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_REOPEN,
					), Yii::t('admin.crud', 'Reopen')); ?>
				
				<?php if(Yii::app()->user->checkAccess('resume_task', array('task' => $model))) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_RESUME,
					), Yii::t('admin.crud', 'Resume')); ?>
			</div>

			<?php $this->endWidget(); ?>
		</div>

	</div>
</div>

<?php

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
	clearInterval(timer);
	window.onbeforeunload = null;
});
EOS
);

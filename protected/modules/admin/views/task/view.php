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
				<li class="task-phase hr">
					<?php echo ViewHelper::taskPhaseIcon($model); ?>
					<?php echo CHtml::encode($model->getPhase()); ?>
				</li>
				<?php if(!empty($model->tags)): ?>
					<li class="tags hr">
						<a class="btn btn-default btn-xs" href="#" title="<?php echo Yii::t('admin.crud', 'Change tags'); ?>">
							<span class="glyphicon glyphicon-tag"></span>
						</a>
						<?php echo Yii::t('task', 'Tags'); ?>
						<ul class="tags">
							<?php foreach($model->tags as $tag): ?>
								<li style="background: <?php echo $tag->color; ?>;">
									<?php echo CHtml::encode($tag->name); ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>
				<li>
					<a class="btn btn-default btn-xs" href="#" title="<?php echo Yii::t('admin.crud', 'Change priority'); ?>">
						<span class="glyphicon glyphicon-arrow-up"></span>
					</a>
					<?php echo ViewHelper::taskPriorityLabel($model); ?>
				</li>
				<li class="hr">
					<a class="btn btn-default btn-xs" href="#" title="<?php echo Yii::t('admin.crud', 'Assign'); ?>">
						<span class="glyphicon glyphicon-user"></span>
					</a>
					<?php if ($model->assigned): ?>
						<?php echo CHtml::encode($model->assigned); ?>
					<?php else: ?>
						<span class="not-set"><?php echo Yii::t('admin.crud', 'Nobody'); ?></span>
					<?php endif; ?>
				</li>
				<li class="timer">
					<a class="btn btn-default btn-xs" href="#" title="<?php echo Yii::t('admin.crud', 'Add'); ?>">
						<span class="glyphicon glyphicon-plus"></span>
					</a>
					<a class="btn btn-default btn-xs" href="#" title="<?php echo Yii::t('admin.crud', 'Start'); ?>">
						<span class="glyphicon glyphicon-play"></span>
					</a>
					<code>0:00:00</code>
				</li>
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

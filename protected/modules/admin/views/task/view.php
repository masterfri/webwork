<?php

$this->pageHeading = Yii::t('admin.crud', 'Task Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project') ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Task') => Yii::app()->user->checkAccess('view_task') ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ' . Yii::t('admin.crud', 'Watch'), 
		'url' => array('watch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task') && $model->user_subscription === null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-close"></i> ' . Yii::t('admin.crud', 'Unwatch'), 
		'url' => array('unwatch', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task') && $model->user_subscription !== null,
	),
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Task'), 
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Task'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Task'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this task?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Task'), 
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_task'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo CHtml::encode($model->name); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'tags:array',
			'milestone',
			array(
				'name' => 'priority',
				'value' => $model->getPriority(),
			),
			'complexity',
			'estimate',
			array(
				'name' => 'regression_risk',
				'value' => $model->getRegressionRisk(),
			),
			'date_sheduled:date',
			'due_date:date',
			array(
				'name' => 'phase',
				'value' => $model->getPhase(),
			),
			'assigned',
			'time_created:datetime',
			'created_by',
		),
	)); ?>
</div>
<?php if ('' != $model->description): ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo Yii::t('task', 'Description'); ?></h3>
		</div>
		<div class="panel-body">
			<?php 
				$this->beginWidget('CMarkdown'); 
				echo $model->description;
				$this->endWidget(); 
			?>
		</div>
	</div>
<?php endif; ?>
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
				<?php if($model->getIsActionAvailable(Task::ACTION_COMMENT)) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-primary',
						'name' => 'action',
						'value' => Task::ACTION_COMMENT,
					), Yii::t('admin.crud', 'Submit')); ?>
				
				<?php if($model->getIsActionAvailable(Task::ACTION_START_WORK)) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_START_WORK,
					), Yii::t('admin.crud', 'Start work')); ?>
					
				<?php if($model->getIsActionAvailable(Task::ACTION_COMPLETE_WORK)) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_COMPLETE_WORK,
					), Yii::t('admin.crud', 'Complete work')); ?>
					
				<?php if($model->getIsActionAvailable(Task::ACTION_RETURN)) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_RETURN,
					), Yii::t('admin.crud', 'Return')); ?>
					
				<?php if($model->getIsActionAvailable(Task::ACTION_CLOSE)) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-warning',
						'name' => 'action',
						'value' => Task::ACTION_CLOSE,
					), Yii::t('admin.crud', 'Close')); ?>
					
				<?php if($model->getIsActionAvailable(Task::ACTION_PUT_ON_HOLD)) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-warning',
						'name' => 'action',
						'value' => Task::ACTION_PUT_ON_HOLD,
					), Yii::t('admin.crud', 'Put on-hold')); ?>
					
				<?php if($model->getIsActionAvailable(Task::ACTION_REOPEN)) 
					echo CHtml::tag('button', array(
						'type' => 'submit',
						'class' => 'btn btn-default',
						'name' => 'action',
						'value' => Task::ACTION_REOPEN,
					), Yii::t('admin.crud', 'Reopen')); ?>
				
				<?php if($model->getIsActionAvailable(Task::ACTION_RESUME)) 
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

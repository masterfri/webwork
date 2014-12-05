<div class="panel panel-default" id="comment-form-container">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('admin.crud', 'Add Comment / Action'); ?></h3>
	</div>
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'comment-form',
		'action' => array('comment', 'id' => $task->id),
		'htmlOptions' => array(
			'class' => 'form',
			'enctype' => 'multipart/form-data',
			'data-raise' => 'ajax-request',
		),
		'enableClientValidation' => false,
	)); ?>
			
		<div class="panel-body">
			<div class="form-content">
				<?php echo $form->errorSummary($comment, null, null, array('class' => 'alert alert-danger')); ?>

				<div class="form-group">
					<?php echo $form->labelEx($comment, 'content', array('class'=>'control-label')); ?>
					<?php echo $form->textArea($comment, 'content', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($comment, 'content', array('class'=>'help-inline')); ?>
				</div>
				
				<div class="form-group">
					<?php echo $form->fileSelectField($comment, 'attachments', array(
						'multiple' => true,
						'maxfiles' => 5,
						'buttonText' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('admin.crud', 'Attach files'),
						'buttonCssClass' => 'btn btn-default',
					)); ?>
				</div>
			</div>
		</div>
			
		<div class="panel-footer">
			<p class="help-block"><?php echo Yii::t('admin.crud', 'Actions marked with * require a comment'); ?></p>
			
			<?php if(Yii::app()->user->checkAccess('comment_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-primary',
					'name' => 'action_type',
					'value' => Task::ACTION_COMMENT,
				), Yii::t('admin.crud', 'Submit') . ' *'); ?>
			
			<?php if(Yii::app()->user->checkAccess('start_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-default',
					'name' => 'action_type',
					'value' => Task::ACTION_START_WORK,
				), Yii::t('admin.crud', 'Start work')); ?>
				
			<?php if(Yii::app()->user->checkAccess('complete_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-default',
					'name' => 'action_type',
					'value' => Task::ACTION_COMPLETE_WORK,
				), Yii::t('admin.crud', 'Complete work')); ?>
				
			<?php if(Yii::app()->user->checkAccess('return_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-default',
					'name' => 'action_type',
					'value' => Task::ACTION_RETURN,
				), Yii::t('admin.crud', 'Return') . ' *'); ?>
				
			<?php if(Yii::app()->user->checkAccess('close_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-warning',
					'name' => 'action_type',
					'value' => Task::ACTION_CLOSE,
				), Yii::t('admin.crud', 'Close')); ?>
				
			<?php if(Yii::app()->user->checkAccess('hold_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-warning',
					'name' => 'action_type',
					'value' => Task::ACTION_PUT_ON_HOLD,
				), Yii::t('admin.crud', 'Put on-hold')); ?>
				
			<?php if(Yii::app()->user->checkAccess('reopen_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-default',
					'name' => 'action_type',
					'value' => Task::ACTION_REOPEN,
				), Yii::t('admin.crud', 'Reopen') . ' *'); ?>
			
			<?php if(Yii::app()->user->checkAccess('resume_task', array('task' => $task))) 
				echo CHtml::tag('button', array(
					'type' => 'submit',
					'class' => 'btn btn-default',
					'name' => 'action_type',
					'value' => Task::ACTION_RESUME,
				), Yii::t('admin.crud', 'Resume')); ?>
		</div>
		<input type="hidden" name="action_type" value="" id="action_type" />
	<?php $this->endWidget(); ?>
</div>

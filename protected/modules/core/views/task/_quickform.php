<div class="form-content">
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'task-form',
		'action' => array('create', 'project' => $project->id),
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'data-raise'=>'ajax-request',
			'enctype' => 'multipart/form-data',
		),
		'enableClientValidation' => false,
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'name', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'name', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'description', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->markdownField($model, 'description', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'description', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'milestone_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'milestone_id', $project->getMilestoneList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'milestone_id', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'priority', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'priority', Task::getListPriorities(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'priority', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'attachments', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->fileSelectField($model, 'attachments', array(
				'multiple' => true,
				'maxfiles' => 10,
				'buttonText' => '<span class="glyphicon glyphicon-paperclip"></span> ' . Yii::t('core.crud', 'Attach files'),
				'buttonCssClass' => 'btn btn-default',
				'pasteTarget' => '#Task_description',
			)); ?>
			<?php echo $form->error($model, 'attachments', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', 'Create'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

<?php

$cs = Yii::app()->clientScript;
$cs->registerScript('quick-create', 
<<<ENDJS
$.ajaxBindings.on('task.created', function() {
	$.fn.yiiListView.update('task-grid');
	$('#task-form').get(0).reset();
	$('#file-select-Task_attachments .file-select-item').remove();
});
ENDJS
);

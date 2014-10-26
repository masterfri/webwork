<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'task-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#task-form").offset().top - 50}, 1000);
				return true;
			}',
		),
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
			<?php echo $form->textArea($model, 'description', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'description', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'tags', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->tagField($model, 'tags', $project->getTagList(), array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'tags', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'milestone_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'milestone_id', $project->getMilestoneList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('admin.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'milestone_id', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'priority', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'priority', Task::getListPriorities(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('admin.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'priority', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'assigned_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'assigned_id', $project->getTeamList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('admin.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'assigned_id', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'regression_risk', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'regression_risk', Task::getListRegressionRisks(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('admin.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'regression_risk', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'complexity', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'complexity', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'complexity', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'estimate', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'estimate', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'estimate', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'date_sheduled', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dateField($model, 'date_sheduled', array(
				'class' => 'form-control datepicker-form-control',
			)); ?> 
			<?php echo $form->error($model, 'date_sheduled', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'due_date', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dateField($model, 'due_date', array(
				'class' => 'form-control datepicker-form-control',
			)); ?> 
			<?php echo $form->error($model, 'due_date', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('admin.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

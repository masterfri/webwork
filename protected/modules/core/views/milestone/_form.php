<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'milestone-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'enctype' => 'multipart/form-data',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#milestone-form").offset().top - 50}, 1000);
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
			<?php echo $form->markdownField($model, 'description', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'description', array('class'=>'help-inline')); ?>
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
				'pasteTarget' => '#Milestone_description',
			)); ?>
			<?php echo $form->error($model, 'attachments', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'date_start', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dateField($model, 'date_start', array(
				'class' => 'form-control datepicker-form-control',
			)); ?> 
			<?php echo $form->error($model, 'date_start', array('class'=>'help-inline')); ?>
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
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'timeentry-form',
		'htmlOptions' => array(
			'data-raise'=>'ajax-request',
		),
		'enableClientValidation' => false,
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
	
	<div class="form-group">
		<?php echo $form->labelEx($model, 'activity_id', array('class'=>'control-label')); ?>
		<?php echo $form->dropdownList($model, 'activity_id', Activity::getList(), array(
			'class' => 'form-control',
			'prompt' => Yii::t('admin.crud', 'Select Value'),
		)); ?> 
		<?php echo $form->error($model, 'activity_id', array('class'=>'help-inline')); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'amount', array('class'=>'control-label')); ?>
		<?php echo $form->textField($model, 'amount', array(
			'class' => 'form-control',
		)); ?> 
		<?php echo $form->error($model, 'amount', array('class'=>'help-inline')); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'description', array('class'=>'control-label')); ?>
		<?php echo $form->textArea($model, 'description', array(
			'class' => 'form-control',
		)); ?> 
		<?php echo $form->error($model, 'description', array('class'=>'help-inline')); ?>
	</div>
	
	<?php echo CHtml::submitButton(Yii::t('admin.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>

	<?php $this->endWidget(); ?>
</div>

<div class="form-content">
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'note-form',
		'htmlOptions' => array(
			'data-raise'=>'ajax-request',
		),
		'enableClientValidation' => false,
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'text', array('class'=>'control-label')); ?>
		<?php echo $form->textArea($model, 'text', array(
			'class' => 'form-control',
		)); ?> 
		<?php echo $form->error($model, 'text', array('class'=>'help-inline')); ?>
	</div>
	<div class="checkbox">
		<?php echo $form->checkbox($model, 'private'); ?> 
		<?php echo $form->labelEx($model, 'private', array('class'=>'control-label')); ?>
		<?php echo $form->error($model, 'text', array('class'=>'help-inline')); ?>
	</div>
	
	<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>

	<?php $this->endWidget(); ?>
</div>

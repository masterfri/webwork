<div class="form-content">
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'task-form',
		'htmlOptions' => array(
			'data-raise'=>'ajax-request',
		),
		'enableClientValidation' => false,
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'complexity', array('class'=>'control-label')); ?>
		<?php echo $form->textField($model, 'complexity', array(
			'class' => 'form-control',
		)); ?> 
		<?php echo $form->error($model, 'complexity', array('class'=>'help-inline')); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'regression_risk', array('class'=>'control-label')); ?>
		<?php echo $form->dropdownList($model, 'regression_risk', Task::getListRegressionRisks(), array(
			'class' => 'form-control',
			'prompt' => Yii::t('admin.crud', 'Select Value'),
		)); ?> 
		<?php echo $form->error($model, 'regression_risk', array('class'=>'help-inline')); ?>
	</div>

	<?php echo CHtml::submitButton(Yii::t('admin.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
	
	<?php $this->endWidget(); ?>
</div>

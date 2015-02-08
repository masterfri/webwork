<div class="form-content">
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'assignment-form',
		'htmlOptions' => array(
			'data-raise'=>'ajax-request',
		),
		'enableClientValidation' => false,
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'user_id', array('class'=>'control-label')); ?>
		<?php echo $form->dropdownList($model, 'user_id', User::getList(), array(
			'class' => 'form-control',
			'prompt' => Yii::t('core.crud', 'Select Value'),
		)); ?> 
		<?php echo $form->error($model, 'user_id', array('class'=>'help-inline')); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'role', array('class'=>'control-label')); ?>
		<?php echo $form->dropdownList($model, 'role', Assignment::getListRoles(), array(
			'class' => 'form-control',
			'prompt' => Yii::t('core.crud', 'Select Value'),
		)); ?> 
		<?php echo $form->error($model, 'role', array('class'=>'help-inline')); ?>
	</div>
	
	<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>

	<?php $this->endWidget(); ?>
</div>

<div class="form-content" data-onload="answerform.loaded">
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'answer-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'data-raise' => 'ajax-request',
		),
		'enableClientValidation' => false,
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'text', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->markdownField($model, 'text', array(
				'class' => 'form-control',
			)); ?>  
			<?php echo $form->error($model, 'text', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'score', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'score', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'score', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

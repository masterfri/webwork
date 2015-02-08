<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'rate-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) {
					$(".rate-matrix-error:visible").each(function() {
						$("#error-summary ul").append($("<li></li>").html($(this).html()));
					});
					$("html, body").animate({scrollTop: $("#rate-form").offset().top - 50}, 1000);
				}
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model->getCompleteMatrix() + array(-1 => $model), null, null, array('class' => 'alert alert-danger', 'id' => 'error-summary')); ?>

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
		<?php echo $form->labelEx($model, 'power', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'power', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'power', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<?php if (count($rates = $model->getCompleteMatrix())): ?>
		<div class="form-group">
			<label class="col-sm-3 control-label">
				<h4><?php echo Yii::t('activityRate', 'Hour Rates'); ?></h4>
			</label>
		</div>
		
		<?php foreach ($rates as $rate): ?>
			<div class="form-group">
				<?php echo $form->labelEx($rate, 'hour_rate', array(
					'class' => 'col-sm-3 control-label',
					'label' => $rate->activity->name,
				)); ?>
				<div class="col-sm-9">
					<?php echo $form->textField($rate, 'hour_rate', array(
						'class' => 'form-control',
						'name' => sprintf('Rate[completeMatrix][%d]', $rate->activity->id),
						'id' => sprintf('Rate_completeMatrix_%d', $rate->activity->id),
					)); ?> 
					<?php echo $form->error($rate, 'hour_rate', array(
						'class'=>'help-inline rate-matrix-error',
						'attributeID' => sprintf('Rate_completeMatrix_%d', $rate->activity->id),
					)); ?>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

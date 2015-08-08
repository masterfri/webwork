<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'working-hours-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#working-hours-form").offset().top - 50}, 1000);
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
		<?php echo $form->labelEx($model, 'mon', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'mon', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'mon', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'fri', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'fri', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'fri', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'tue', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'tue', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'tue', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'sat', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'sat', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'sat', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'wed', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'wed', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'wed', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'sun', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'sun', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'sun', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'thu', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'thu', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'thu', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'general', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<div class="checkbox">
				<?php echo $form->checkbox($model, 'general'); ?> 
			</div>
			<?php echo $form->error($model, 'general', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

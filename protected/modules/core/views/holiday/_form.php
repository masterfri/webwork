<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'holiday-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#holiday-form").offset().top - 50}, 1000);
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
		<?php echo $form->label($model, 'date', array('class'=>'col-sm-3 control-label', 'required' => true)); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'day', Holiday::getDaysList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'day', array('class'=>'help-inline')); ?>
		</div>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'month', Holiday::getMonthList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'month', array('class'=>'help-inline')); ?>
		</div>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'year', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'year', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->label($model, 'date2', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'day2', Holiday::getDaysList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'day2', array('class'=>'help-inline')); ?>
		</div>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'month2', Holiday::getMonthList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'month2', array('class'=>'help-inline')); ?>
		</div>
		<div class="col-sm-3">
			<?php echo $form->textField($model, 'year2', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'year2', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'for', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->tagField($model, 'for', User::getList(), array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'for', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'candidate-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#candidate-form").offset().top - 50}, 1000);
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
		<?php echo $form->labelEx($model, 'notes', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textArea($model, 'notes', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'notes', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'lang', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'lang', Candidate::getListLocales(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'lang', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'level', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'level', Candidate::getListLevels(), array(
				'class'=>'form-control', 
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'level', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'questions_limit', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'questions_limit', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'questions_limit', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'categories', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->tagField($model, 'categories', QuestionCategory::getList(), array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'categories', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

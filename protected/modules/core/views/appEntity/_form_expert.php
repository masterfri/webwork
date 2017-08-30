<div class="form-content">
		
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'appEntity-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#appEntity-form").offset().top - 50}, 1000);
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
	
	<div class="form-group">
		<div class="col-sm-12">
			<?php echo $form->textArea($model, 'plain_source', array(
				'class' => 'form-control',
				'rows' => 40,
				'cols' => 40,
			)); ?> 
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-5">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary', 'id' => 'submit-button')); ?>
		</div>
	</div>
	
	<?php $this->endWidget(); ?>
</div>

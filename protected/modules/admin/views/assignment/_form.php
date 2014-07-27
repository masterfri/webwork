<div class="form-content">
	
	
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'assignment-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#assignment-form").offset().top - 50}, 1000);
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'user_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'user_id', User::getList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('admin.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'user_id', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'role', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'role', Assignment::getListRoles(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('admin.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'role', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('admin.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

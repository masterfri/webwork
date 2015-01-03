<div class="form-content">
	
	
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id' => 'user-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#user-form").offset().top - 50}, 1000);
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'real_name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'real_name', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'real_name', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'username', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'username', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'username', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'email', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'email', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'email', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'password_plain', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->passwordField($model, 'password_plain', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'password_plain', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'password_confirm', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->passwordField($model, 'password_confirm', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'password_confirm', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'locale', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'locale', User::getListLocales(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('admin.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'status', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('admin.crud', 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

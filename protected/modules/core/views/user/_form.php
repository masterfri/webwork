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
		<?php echo $form->labelEx($model, 'role', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'role', User::listRoles(), array(
				'class'=>'form-control', 
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'role', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'rate_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'rate_id', Rate::getList(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'rate_id', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'working_hours_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'working_hours_id', WorkingHours::getList(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('workingHours', 'General'),
			)); ?>
			<?php echo $form->error($model, 'working_hours_id', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'status', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'status', User::getListStatuses(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'status', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'locale', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'locale', User::getListLocales(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'status', array('class'=>'help-inline')); ?>
		</div>
		<?php echo $form->labelEx($model, 'document_locale', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dropdownList($model, 'document_locale', User::getListLocales(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'document_locale', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-9 col-sm-offset-3">
			<h4><?php echo Yii::t('user', 'Legal Entity') ?></h4>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'legal_name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'legal_name', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'legal_name', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'legal_type', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'legal_type', User::getListLegalTypes(), array(
				'class'=>'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?>
			<?php echo $form->error($model, 'legal_type', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'legal_signer_name', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'legal_signer_name', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'legal_signer_name', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'legal_number', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'legal_number', array(
				'class'=>'form-control',
			)); ?>
			<?php echo $form->error($model, 'legal_number', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'legal_address', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textArea($model, 'legal_address', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'legal_address', array('class'=>'help-inline')); ?>
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

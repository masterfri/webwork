<div class="form-content">
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'invoice-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#invoice-form").offset().top - 50}, 1000);
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model, 'from_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'from_id', User::getList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'from_id', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'to_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'to_id', User::getList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'to_id', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'project_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'project_id', Project::getUserBundleList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'project_id', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'comments', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textArea($model, 'comments', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'comments', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<?php if(!$model->getIsNewRecord()): ?>
		<div class="form-group">
			<?php echo $form->labelEx($model, 'draft', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'draft', array(
					1 => Yii::t('core.crud', 'Yes'),
					0 => Yii::t('core.crud', 'No'),
				), array(
					'class' => 'form-control',
					'prompt' => Yii::t('core.crud', 'Select Value'),
				)); ?> 
				<?php echo $form->error($model, 'draft', array('class'=>'help-inline')); ?>
			</div>
		</div>
	<?php endif; ?>
	
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

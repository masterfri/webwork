<div class="form-content">
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'completion-report-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#completion-report-form").offset().top - 50}, 1000);
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<?php if($model->getIsNewRecord()): ?>
		<div class="form-group">
			<?php echo $form->labelEx($model, 'performer_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'performer_id', User::getList(), array(
					'class' => 'form-control',
					'prompt' => Yii::t('core.crud', 'Select Value'),
				)); ?> 
				<?php echo $form->error($model, 'performer_id', array('class'=>'help-inline')); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model, 'contragent_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'contragent_id', User::getList(), array(
					'class' => 'form-control',
					'prompt' => Yii::t('core.crud', 'Select Value'),
				)); ?> 
				<?php echo $form->error($model, 'contragent_id', array('class'=>'help-inline')); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model, 'contract_number', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-3">
				<?php echo $form->textField($model, 'contract_number', array(
					'class' => 'form-control',
				)); ?> 
				<?php echo $form->error($model, 'contract_number', array('class'=>'help-inline')); ?>
			</div>
			<?php echo $form->labelEx($model, 'contract_date', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-3">
				<?php echo $form->dateField($model, 'contract_date', array(
					'class' => 'form-control datepicker-form-control',
				)); ?> 
				<?php echo $form->error($model, 'contract_date', array('class'=>'help-inline')); ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'date', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-3">
			<?php echo $form->dateField($model, 'date', array(
				'class' => 'form-control datepicker-form-control',
			)); ?> 
			<?php echo $form->error($model, 'date', array('class'=>'help-inline')); ?>
		</div>
		<?php if(!$model->getIsNewRecord()): ?>
			<?php echo $form->labelEx($model, 'draft', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-3">
				<?php echo $form->dropdownList($model, 'draft', array(
					1 => Yii::t('core.crud', 'Yes'),
					0 => Yii::t('core.crud', 'No'),
				), array(
					'class' => 'form-control',
					'prompt' => Yii::t('core.crud', 'Select Value'),
				)); ?> 
				<?php echo $form->error($model, 'draft', array('class'=>'help-inline')); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php if($model->getIsNewRecord()): ?>
		<div class="form-group">
			<?php echo $form->labelEx($model, 'collect_jobs', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'collect_jobs', array(
					1 => Yii::t('core.crud', 'Yes'),
					0 => Yii::t('core.crud', 'No'),
				), array(
					'class' => 'form-control',
					'data-show-if' => '#collect-jobs',
				)); ?> 
				<?php echo $form->error($model, 'collect_jobs', array('class'=>'help-inline')); ?>
			</div>
		</div>
		<div id="collect-jobs" style="display: none">
			<div class="form-group">
				<?php echo $form->label($model, 'projects', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->tagField($model, 'projects', null, array(
						'ajax' => array(
							'url' => $this->createUrl('project/query'),
						),
					)); ?> 
					<?php echo $form->error($model, 'projects', array('class'=>'help-inline')); ?>
				</div>
			</div>
			<div class="form-group">
			<?php echo $form->labelEx($model, 'date_from', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->dateField($model, 'date_from', array(
						'class' => 'form-control datepicker-form-control',
					)); ?> 
					<?php echo $form->error($model, 'date_from', array('class'=>'help-inline')); ?>
				</div>
				<?php echo $form->labelEx($model, 'date_to', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->dateField($model, 'date_to', array(
						'class' => 'form-control datepicker-form-control',
					)); ?> 
					<?php echo $form->error($model, 'date_to', array('class'=>'help-inline')); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'conversion_rate', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->textField($model, 'conversion_rate', array(
						'class' => 'form-control',
					)); ?>
					<?php echo $form->error($model, 'conversion_rate', array('class'=>'help-inline')); ?>
				</div>
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

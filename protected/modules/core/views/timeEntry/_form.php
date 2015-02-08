<div class="form-content">
	<?php $form=$this->beginWidget('ActiveForm', array(
		'id' => 'timeentry-form',
		'htmlOptions' => array(
			'class'=>'form-horizontal',
		),
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
			'afterValidate' => 'js:function(f,d,e) {
				if (e) $("html, body").animate({scrollTop: $("#timeentry-form").offset().top - 50}, 1000);
				return true;
			}',
		),
	)); ?>
	
	<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>

	<?php if ($model->getIsNewRecord()): ?>
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
		<div class="form-group" style="display: <?php echo $model->project_id > 0 ? 'block' : 'none'; ?>">
			<?php echo $form->labelEx($model, 'task_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->selectField($model, 'task_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('task/query'),
						'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#TimeEntry_project_id").val()}; }',
					),
				)); ?> 
				<?php echo $form->error($model, 'task_id', array('class'=>'help-inline')); ?>
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model, 'user_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->selectField($model, 'user_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('user/query'),
						'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#TimeEntry_project_id").val()}; }',
					),
				)); ?> 
				<?php echo $form->error($model, 'user_id', array('class'=>'help-inline')); ?>
			</div>
		</div>
	<?php endif; ?>
	
	<div class="form-group">
		<?php echo $form->labelEx($model, 'activity_id', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->dropdownList($model, 'activity_id', Activity::getList(), array(
				'class' => 'form-control',
				'prompt' => Yii::t('core.crud', 'Select Value'),
			)); ?> 
			<?php echo $form->error($model, 'activity_id', array('class'=>'help-inline')); ?>
		</div>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model, 'amount', array('class'=>'col-sm-3 control-label')); ?>
		<div class="col-sm-9">
			<?php echo $form->textField($model, 'formattedAmount', array(
				'class' => 'form-control',
			)); ?> 
			<?php echo $form->error($model, 'formattedAmount', array('class'=>'help-inline')); ?>
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
		<div class="col-sm-offset-3 col-sm-9">
			<?php echo CHtml::submitButton(Yii::t('core.crud', $model->isNewRecord ? 'Create' : 'Update'), array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

	<?php $this->endWidget(); ?>
</div>

<?php

Yii::app()->clientScript->registerScript('time-entry-form', "
$('#TimeEntry_project_id').on('change', function() {
	if ('' == $(this).val()) {
		$('#TimeEntry_task_id').select2('val', '').closest('.form-group').hide();
	} else {
		$('#TimeEntry_task_id').closest('.form-group').show();
	}
});
");

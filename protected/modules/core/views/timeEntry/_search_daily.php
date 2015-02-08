<div class="form-content">

	<?php $form = $this->beginWidget('ActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'role' => 'search-form',
			'data-target' => 'timeentry-grid',
		),
		'method' => 'get',
	)); ?>
		<div class="form-group">
			<?php echo $form->label($model, 'date_created', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dateField($model, 'date_created', array(
					'class' => 'form-control datepicker-form-control',
				)); ?> 
			</div>
		</div>
		
		<?php if (Yii::app()->user->checkAccess('view_time_entry', array('entry' => '*'))): ?>
			<div class="form-group">
				<?php echo $form->label($model, 'user_id', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->tagField($model, 'user_id', null, array(
						'ajax' => array(
							'url' => $this->createUrl('user/query'),
						),
					)); ?> 
				</div>
			</div>
		<?php endif; ?>
		
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<?php echo CHtml::submitButton(Yii::t('core.crud', 'Search'), array('class'=>'btn btn-default')); ?>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>

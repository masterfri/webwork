<div class="form-content">

	<?php $form = $this->beginWidget('ActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'role' => 'search-form',
			'data-target' => 'invoice-grid',
		),
		'method' => 'get',
	)); ?>
		<div class="form-group">
			<?php echo $form->label($model, 'from_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'from_id', User::getList(), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'to_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'to_id', User::getList(), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'project_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'project_id', Project::getList(), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<?php echo CHtml::submitButton(Yii::t('admin.crud', 'Search'), array('class'=>'btn btn-default')); ?>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>

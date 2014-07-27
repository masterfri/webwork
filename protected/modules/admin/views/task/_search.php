<div class="form-content">

	<?php $form = $this->beginWidget('ActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'role' => 'search-form',
			'data-target' => 'task-grid',
		),
		'method' => 'get',
	)); ?>
		<div class="form-group">
			<?php echo $form->label($model, 'name', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->textField($model, 'name', array(
					'class' => 'form-control',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'priority', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'priority', array(
					1 => 'Critical',
					2 => 'Urgent',
					3 => 'High',
					4 => 'Medium',
					5 => 'Low',
					6 => 'On hold',
				), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'regression_risk', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'regression_risk', array(
					1 => 'High',
					2 => 'Medium',
					3 => 'Low',
					4 => 'None',
				), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'phase', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'phase', array(
					1 => 'Created',
					2 => 'Scheduled',
					3 => 'In Progress',
					4 => 'Pending',
					5 => 'New Iteration',
					6 => 'Closed',
					7 => 'Paused',
				), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'assigned_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'assigned_id', User::getList(), array(
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

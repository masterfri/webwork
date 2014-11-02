<div class="form-content">

	<?php $form = $this->beginWidget('ActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'role' => 'search-form',
			'data-target' => 'task-grid',
			'data-target-type' => 'listview',
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
			<?php echo $form->labelEx($model, 'milestone_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'milestone_id', $project->getMilestoneList()); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'tags', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'tags', $project->getTagList(), array(
					'class' => 'form-control',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'priority', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'priority', Task::getListPriorities()); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'regression_risk', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'regression_risk', Task::getListRegressionRisks()); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'phase', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'phase', array(
					Task::PHASE_CREATED => Yii::t('task', 'New'),
					Task::PHASE_SCHEDULED => Yii::t('task', 'Scheduled'),
					Task::PHASE_IN_PROGRESS => Yii::t('task', 'In progress'),
					Task::PHASE_PENDING => Yii::t('task', 'Pending'),
					Task::PHASE_NEW_ITERATION => Yii::t('task', 'New iteration'),
					Task::PHASE_CLOSED => Yii::t('task', 'Closed'),
					Task::PHASE_ON_HOLD => Yii::t('task', 'On-hold'),
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'assigned_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'assigned_id', $project->getTeamList()); ?> 
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<?php echo CHtml::submitButton(Yii::t('admin.crud', 'Search'), array('class'=>'btn btn-default')); ?>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>

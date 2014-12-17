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
					'mode' => 'months',
					'format' => 'm/Y',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'project_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'project_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('project/query'),
					),
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'milestone_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'milestone_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('milestone/query'),
						'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#TimeEntry_project_id").tagval()}; }',
					),
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'task_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'task_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('task/query'),
						'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#TimeEntry_project_id").tagval()}; }',
					),
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
							'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#TimeEntry_project_id").tagval()}; }',
						),
					)); ?> 
				</div>
			</div>
		<?php endif; ?>
		<div class="form-group">
			<?php echo $form->label($model, 'activity_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'activity_id', Activity::getList(), array(
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

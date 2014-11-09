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
			<?php echo $form->label($model, 'project_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'project_id', Project::getList(), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'task_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->selectField($model, 'task_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('task/query'),
						'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#TimeEntry_project_id").val()}; }',
					),
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'user_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->selectField($model, 'user_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('user/query'),
						'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#TimeEntry_project_id").val()}; }',
					),
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'activity_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'activity_id', Activity::getList(), array(
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

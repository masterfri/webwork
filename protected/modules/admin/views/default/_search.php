<div class="form-content">

	<?php $form = $this->beginWidget('ActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'htmlOptions' => array(
			'role' => 'search-form',
			'data-target' => 'task-grid',
			'data-target-type' => 'listview',
		),
		'method' => 'get',
	)); ?>
		
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<?php echo $form->labelEx($model, 'project_id', array('class'=>'control-label')); ?>
					<?php echo $form->tagField($model, 'project_id', Project::getList()); ?> 
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<?php echo $form->labelEx($model, 'milestone_id', array('class'=>'control-label')); ?>
					<?php echo $form->tagField($model, 'milestone_id', null, array(
						'ajax' => array(
							'url' => $this->createUrl('milestone/query'),
							'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#Task_project_id").val()}; }',
						),
					)); ?> 
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<?php echo $form->labelEx($model, 'tags', array('class'=>'control-label')); ?>
					<?php echo $form->tagField($model, 'tags', null, array(
						'ajax' => array(
							'url' => $this->createUrl('tag/query'),
							'data' => 'js:function(t, p) { return {query: t, page: p, project: $("#Task_project_id").val()}; }',
						),
					)); ?> 
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<?php echo $form->labelEx($model, 'priority', array('class'=>'control-label')); ?>
					<?php echo $form->tagField($model, 'priority', Task::getListPriorities()); ?> 
				</div>
			</div>
		</div>

		<?php echo CHtml::submitButton(Yii::t('admin.crud', 'Search'), array('class'=>'btn btn-default')); ?>

	<?php $this->endWidget(); ?>

</div>

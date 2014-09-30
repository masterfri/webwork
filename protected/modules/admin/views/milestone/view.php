<?php

$this->pageHeading = Yii::t('admin.crud', 'Milestone Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Milestone') => Yii::app()->user->checkAccess('view_milestone', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Milestone'), 
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_milestone', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list"></i> ' . Yii::t('admin.crud', 'Task'), 
		'url' => array('task/index', 'project' => $model->project->id, 'milestone' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Milestone'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_milestone', array('milestone' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Milestone'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this milestone?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_milestone', array('milestone' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Milestone'), 
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_milestone', array('project' => $model->project)),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo CHtml::encode($model->name); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'due_date:date',
			'time_created:datetime',
			'created_by',
		),
	)); ?>
	<div class="panel-body">
		<?php if ('' != $model->description): ?>
			<?php 
				$this->beginWidget('CMarkdown'); 
				echo $model->description;
				$this->endWidget(); 
			?>
		<?php else: ?>
			<p class="not-set"><?php echo Yii::t('admin.crud', 'No description given'); ?></p>
		<?php endif; ?>
	</div>
</div>

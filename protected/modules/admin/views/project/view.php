<?php

$this->pageHeading = Yii::t('admin.crud', 'Project Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Project'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_project'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('admin.crud', 'Assignment'), 
		'url' => array('assignment/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_assignment'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-calendar"></i> ' . Yii::t('admin.crud', 'Milestone'), 
		'url' => array('milestone/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_milestone'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list"></i> ' . Yii::t('admin.crud', 'Task'), 
		'url' => array('task/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Project'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_project'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Project'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this project?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_project'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Project'), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_project'),
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
			'assignments:array',
			'date_created:datetime',
			'created_by',
		),
	)); ?>
</div>
<?php if ('' != $model->scope): ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo Yii::t('project', 'Scope'); ?></h3>
		</div>
		<div class="panel-body">
			<?php 
				$this->beginWidget('CMarkdown'); 
				echo $model->scope;
				$this->endWidget(); 
			?>
		</div>
	</div>
<?php endif; ?>

<?php

$this->pageHeading = Yii::t('admin.crud', 'Project Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Project'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_project'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-calendar"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Milestones'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('milestone/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_milestone', array('project' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-tasks"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Tasks'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('task/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('project' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Manage Projects'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_project'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>', 
		'linkOptions' => array(
			'class' => 'btn btn-default dropdown-toggle',
			'data-toggle' => 'dropdown',
		),
		'itemOptions' => array(
			'class' => 'dropdown',
		),
		'items' => array(
			array(
				'label' => '<i class="glyphicon glyphicon-file"></i> ' . Yii::t('admin.crud', 'Specification'), 
				'url' => array('pdf', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('admin.crud', 'Team'), 
				'url' => array('assignment/index', 'project' => $model->id),
				'visible' => Yii::app()->user->checkAccess('view_assignment', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Project'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-save"></i> ' . Yii::t('admin.crud', 'Archive Project'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('archive', 'id' => $model->id),
					'confirm' => Yii::t('admin.crud', 'Are you sure you want to archive this project?'),
				),
				'visible' => $model->archived == 0 && Yii::app()->user->checkAccess('archive_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-open"></i> ' . Yii::t('admin.crud', 'Activate Project'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('activate', 'id' => $model->id),
					'confirm' => Yii::t('admin.crud', 'Are you sure you want to activate this project?'),
				),
				'visible' => $model->archived == 1 && Yii::app()->user->checkAccess('activate_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Project'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this project?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_project', array('project' => $model)),
			),
		),
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
		),
	)); ?>
	<div class="panel-body">
		<?php if ('' != $model->scope): ?>
			<?php 
				$this->beginWidget('CMarkdown'); 
				echo $model->scope;
				$this->endWidget(); 
			?>
		<?php else: ?>
			<p class="not-set"><?php echo Yii::t('admin.crud', 'No description given'); ?></p>
		<?php endif; ?>
	</div>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('project', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->date_created); ?>
	</div>
</div>

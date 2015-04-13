<?php

$this->pageHeading = Yii::t('core.crud', 'Application Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('core.crud', 'Applications') => Yii::app()->user->checkAccess('view_application', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Application'),
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_application', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Applications'),
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_application', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Project'),
			'class' => 'btn btn-default',
		),
		'url' => array('project/view', 'id' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)),
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Application'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_application', array('application' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-globe"></i> ' . Yii::t('core.crud', 'Configure Web Server'), 
				'url' => array('configWeb', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_application', array('application' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-cloud"></i> ' . Yii::t('core.crud', 'Configure Git'), 
				'url' => array('configGit', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_application', array('application' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-cloud-download"></i> ' . Yii::t('core.crud', 'Pull From Repo'), 
				'url' => array('pull', 'id' => $model->id),
				'visible' => ($model->status & Application::STATUS_HAS_GIT) && Yii::app()->user->checkAccess('pull_application', array('application' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-hdd"></i> ' . Yii::t('core.crud', 'Configure Database'), 
				'url' => array('configDb', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_application', array('application' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Application'), 
				'url' => array('delete', 'id' => $model->id), 
				'visible' => Yii::app()->user->checkAccess('delete_application', array('application' => $model)),
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
			'name',
			'description:ntext',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('application', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

<?php if($model->status & Application::STATUS_HAS_WEB): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('application', 'Web Server'); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			array(
				'name' => 'domain',
				'type' => 'raw',
				'value' => CHtml::link($model->getDomain(), sprintf('http://%s/', $model->getDomain()), array('target' => '_blank')),
			),
			'document_root',
			'log_directory',
			'vhost_options:ntext',
		),
	)); ?>
</div>
<?php endif; ?>

<?php if($model->status & Application::STATUS_HAS_GIT): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('application', 'Git Repository'); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'git',
			'git_branch',
		),
	)); ?>
</div>
<?php endif; ?>

<?php if($model->status & Application::STATUS_HAS_DB): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('application', 'Database'); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'db_name',
			'db_user',
			'db_password',
		),
	)); ?>
</div>
<?php endif; ?>

<?php

$this->pageHeading = Yii::t('admin.crud', 'Member Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Team') => Yii::app()->user->checkAccess('view_assignment', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Add a Member'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_assignment', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i> ',
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Team'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_assignment', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Back to Project'), 
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Member'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_assignment', array('assignment' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Member'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this member?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_assignment', array('assignment' => $model)),
			),
		),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'user',
			array('name' => 'role', 'value' => $model->getRoleName()),
		),
	)); ?>
</div>

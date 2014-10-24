<?php

$this->pageHeading = Yii::t('admin.crud', 'Add Comment / Action');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Project') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('admin.crud', 'Task') => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Back to task'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('task' => $model)),
	),
);

$this->renderPartial('_comment_form', array(
	'task' => $model,
	'comment' => $comment,
)); 

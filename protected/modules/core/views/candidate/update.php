<?php

$this->pageHeading = Yii::t('core.crud', 'Candidate Updating');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Candidates') => Yii::app()->user->checkAccess('view_candidate') ? array('index') : false, 
	Yii::t('core.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Candidate'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_candidate'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'View Candidate'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_candidate'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Candidates'), 
			'class' => 'btn btn-default',
		), 
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_candidate'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form', array(
			'model' => $model,
		)); ?>
	</div>
</div>

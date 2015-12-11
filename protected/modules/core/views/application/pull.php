<?php

$this->pageHeading = Yii::t('core.crud', 'Pull Application');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('core.crud', 'Applications') => Yii::app()->user->checkAccess('view_application', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
	Yii::t('core.crud', 'Pull'),
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
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'View Application'),
			'class' => 'btn btn-default',
		),
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_application', array('application' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Applications'),
			'class' => 'btn btn-default',
		),
		'url'=>array('index', 'project' => $model->project->id),
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
);

Yii::app()->clientScript->registerScript('processing', "
$('#pull-form').on('submit', function() {
	$(this).children('.preloader').show();
	$(this).children('.btn').attr('disabled', 'disabled');
});
");

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,		
		'attributes' => array(
			'git',
			'git_branch',
		),
	)); ?>
	<div class="panel-body">
		<?php if ($response): ?>
			<?php if (!$response->getIsSuccess()): ?>
				<div class="alert alert-danger">
					<?php echo CHtml::encode($response->getMessage()); ?>
				</div>
				<pre><?php echo CHtml::encode($response->getRaw()); ?></pre>
			<?php else: ?>
				<div class="alert alert-success">
					<?php echo Yii::t('core.crud', 'Pull has been successfully completed'); ?>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<form action="" method="post" id="pull-form">
				<input name="make_pull" type="hidden" value="1" />
				<div style="display: none;" class="preloader">
					<p><?php echo Yii::t('core.crud', 'Operation in process... Please, be patient.'); ?></p>
				</div>
				<button type="submit" class="btn btn-primary"><?php echo Yii::t('core.crud', 'Make Pull'); ?></button>
			</form>
		<?php endif; ?>
	</div>
</div>

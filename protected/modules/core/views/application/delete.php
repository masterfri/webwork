<?php

$this->pageHeading = Yii::t('core.crud', 'Delete Application');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('core.crud', 'Applications') => Yii::app()->user->checkAccess('view_application', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
	Yii::t('core.crud', 'Delete'),
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
$('#delete-form').on('submit', function() {
	$(this).children('.preloader').show();
	$(this).children('.options').hide();
	$(this).children('.btn').attr('disabled', 'disabled');
});
");

if (null !== $response) {
	$this->renderPartial('_httpsh_response', array(
		'response' => $response,
	));
}

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<form action="" method="post" id="delete-form">
			<input name="delete" type="hidden" value="1" />
			<div class="options">
				<p><?php echo Yii::t('core.crud', 'You are going to delete the application `{app}`. Please, confirm your intention by clicking `Delete`. Optionally you can delete following:', array(
					'{app}' => CHtml::encode($model->name),
				)); ?></p>
				<p>
					<label><?php echo CHtml::checkbox('opts[files]'); ?> <?php echo Yii::t('application', 'Application files'); ?></label>
					<?php if ($model->status & Application::STATUS_HAS_WEB): ?>
						<br />
						<label><?php echo CHtml::checkbox('opts[vhost]'); ?> <?php echo Yii::t('application', 'Virtual host'); ?></label>
					<?php endif; ?>
					<?php if ($model->getIsGitLocal()): ?>
						<br />
						<label><?php echo CHtml::checkbox('opts[git]'); ?> <?php echo Yii::t('application', 'Git repository `{repo}`', array(
							'{repo}' => CHtml::encode($model->git),
						)); ?></label>
					<?php endif; ?>
					<?php if ($model->status & Application::STATUS_HAS_DB): ?>
						<br />
						<label><?php echo CHtml::checkbox('opts[db]'); ?> <?php echo Yii::t('application', 'MySQL database `{db}`', array(
							'{db}' => CHtml::encode($model->db_name),
						)); ?></label>
						<br />
						<label><?php echo CHtml::checkbox('opts[dbuser]'); ?> <?php echo Yii::t('application', 'MySQL user `{user}`', array(
							'{user}' => CHtml::encode($model->db_user),
						)); ?></label>
					<?php endif; ?>
				</p>
			</div>
			<div style="display: none;" class="preloader">
				<p><?php echo Yii::t('core.crud', 'Operation in process... Please, be patient.'); ?></p>
			</div>
			<button type="submit" class="btn btn-danger"><?php echo Yii::t('core.crud', 'Delete'); ?></button>
		</form>
	</div>
</div>

<?php

$this->pageHeading = Yii::t('core.crud', 'Cleanup Application');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($application->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $application->project)) ? array('project/view', 'id' => $application->project->id) : false, 
	CHtml::encode($application->name) => Yii::app()->user->checkAccess('view_application', array('application' => $application)) ? array('application/view', 'id' => $application->id) : false, 
	Yii::t('core.crud', 'Cleanup')
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'application' => $application->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $application)),
	)
);

Yii::app()->clientScript->registerScript('processing', "
$('#cleanup-form').on('submit', function() {
	$(this).children('.preloader').show();
	$(this).children('.options').hide();
	$(this).children('.btn').attr('disabled', 'disabled');
});
");

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<form action="" method="post" id="cleanup-form">
			<input name="cleanup" type="hidden" value="1" />
			<div class="options">
				<p><?php echo Yii::t('core.crud', 'Please select directories that you want to cleanup:'); ?></p>
				<p>
					<div class="form-group">
						<label><?php echo CHtml::checkbox('git'); ?> <?php echo Yii::t('application', 'Working copy (build)'); ?></label>
					</div>
					<div class="form-group">
						<label><?php echo CHtml::radiobutton('build', '', array('value' => 'compiled')); ?> <?php echo Yii::t('application', 'Compiled files'); ?></label>
						<br />
						<label><?php echo CHtml::radiobutton('build', '', array('value' => 'all')); ?> <?php echo Yii::t('application', 'Whole build directory (will reset build options)'); ?></label>
					</div>
				</p>
			</div>
			<div style="display: none;" class="preloader">
				<p><?php echo Yii::t('core.crud', 'Operation in process... Please, be patient.'); ?></p>
			</div>
			<button type="submit" class="btn btn-danger"><?php echo Yii::t('core.crud', 'Cleanup'); ?></button>
		</form>
	</div>
</div>

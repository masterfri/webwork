<?php

$this->pageHeading = Yii::t('core.crud', 'Application Updating');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('core.crud', 'Application') => Yii::app()->user->checkAccess('view_application', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
	Yii::t('core.crud', 'Update'),
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

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div class="form-content">
			<?php $form=$this->beginWidget('ActiveForm', array(
				'id' => 'application-form',
				'htmlOptions' => array(
					'class'=>'form-horizontal',
				),
				'enableClientValidation' => true,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'afterValidate' => 'js:function(f,d,e) {
						if (e) $("html, body").animate({scrollTop: $("#application-form").offset().top - 50}, 1000);
						return true;
					}',
				),
			)); ?>
			
			<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
			
			<div class="form-group">
				<?php echo $form->labelEx($model, 'name', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->textField($model, 'name', array(
						'class' => 'form-control',
						'disabled' => true,
					)); ?> 
					<?php echo $form->error($model, 'name', array('class'=>'help-inline')); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model, 'description', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->textArea($model, 'description', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'description', array('class'=>'help-inline')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<?php echo CHtml::submitButton(Yii::t('core.crud', 'Update'), array('class'=>'btn btn-primary')); ?>
				</div>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
